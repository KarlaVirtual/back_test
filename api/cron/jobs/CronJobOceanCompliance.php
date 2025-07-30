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
use Backend\dto\Usuario;
use Backend\dto\UsuarioMandante;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\TransaccionApi;
use Backend\dto\LealtadInterna;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioAutomation;
use Backend\dto\UsuarioHistorial;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioAutomationMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;



/**
 * Clase 'CronJobOceanCompliance'
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
class CronJobOceanCompliance
{


    public function __construct()
    {
    }

    public function execute()
    {


        try {

            $date1 = date('Y-m-d 00:00:00', strtotime('-37 days'));

            $date2 = date('Y-m-d 23:59:59', strtotime('-37 days'));

            $sql =
                "SELECT usuario.usuario_id,
       usuario.login,
       usuario.fecha_crea,
       usuario.fecha_ult,
       usuario.nombre,
       registro.direccion,
       registro.nombre1,
       registro.apellido1,
       ciudad.ciudad_nom,
       departamento.depto_nom,
       registro.codigo_postal,
       pais.pais_nom,
       registro.celular,
       registro.email
FROM usuario
         INNER JOIN pais ON (usuario.pais_id = pais.pais_id)
         INNER JOIN usuario_perfil   ON (usuario_perfil.usuario_id=usuario.usuario_id)
         LEFT OUTER JOIN registro ON (registro.usuario_id = usuario.usuario_id)
         LEFT OUTER JOIN ciudad ON (registro.ciudad_id = ciudad.ciudad_id)
        LEFT OUTER JOIN departamento ON (departamento.depto_id=ciudad.depto_id)
WHERE usuario.mandante IN ('14','0','8')
  AND usuario.estado = 'A'
  AND usuario.pais_id IN('33','46','66')
  AND usuario_perfil.perfil_id = 'USUONLINE'
  AND  usuario.fecha_crea >= '$date1'
  AND  usuario.fecha_crea <= '$date2'

  ORDER BY usuario.usuario_id ";

            print_r($sql);

            $Usuario = new \Backend\dto\Usuario();
            $BonoInterno = new \Backend\dto\BonoInterno();

            $UsuarioMySqlDAO = new \Backend\mysql\UsuarioMySqlDAO();

            $transaccion = $UsuarioMySqlDAO->getTransaction();

            //$transaccion->getConnection()->beginTransaction();
            $Usuarios = $BonoInterno->execQuery($transaccion, $sql);
            $Usuarios = json_decode(json_encode($Usuarios), TRUE);

            function quitar_tildes($cadena)
            {
                $no_permitidas = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®", "Ã´", "Ã»", "Ã‚", "ÃŠ", "ÃŽ", "Ã”", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "«", "Ò", "Ã", "Ã„", "Ã‹");
                $permitidas = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "c", "C", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E");
                $texto = str_replace($no_permitidas, $permitidas, $cadena);
                return $texto;
            }

            $users = array();
            $array = array();
            $array["UID"] = 'UID';
            $array["Username"] = 'Username';
            $array["Member Since"] = 'Member Since';
            $array["Last Login"] = 'Last Login';
            $array["Full Name"] = 'Full Name';
            $array["Address"] = 'Address';
            $array["City"] = 'City';
            $array["State"] = 'State';
            $array["PostalCode"] = 'PostalCode';
            $array["Country"] = 'Country';
            $array["Phone"] = 'Phone';
            $array["Email"] = 'Email';


            array_push($users, $array);
            foreach ($Usuarios as $key => $value) {
                $array = array();
                $array["UID"] = $value["usuario.usuario_id"];
                $array["Username"] = $value["registro.nombre1"] . $value["registro.apellido1"];
                $array["Member Since"] = $value["usuario.fecha_crea"];
                $array["Last Login"] = $value["usuario.fecha_ult"];
                $array["Full Name"] = $value["usuario.nombre"];
                $array["Address"] = $value["registro.direccion"];
                $array["City"] = quitar_tildes($value["ciudad.ciudad_nom"]);
                $array["State"] = quitar_tildes($value["departamento.depto_nom"]);
                $array["PostalCode"] = $value["registro.codigo_postal"];
                $array["Country"] = quitar_tildes($value["pais.pais_nom"]);
                $array["Phone"] = "";
                $array["Email"] = "";


                array_push($users, $array);
            }


//$users = array_push($users, $keys);

            $json = json_encode($users);

            $jsonans = json_decode($json, 'verdadero');

            $f = fopen(__DIR__ . '/userdb_' . date("Y-m-d", strtotime('-37 days')) . '.csv', 'w');

            foreach ($jsonans as $object) {

                fputcsv($f, $object);
            }

            //fclose( $f );


            //$transaccion->commit();


            $server = 'ds.gaming-curacao.com';
            $usuario = 'vsnetsolnv';
            $pass = '!jHEUNeys8bZ';

            $host = $server;
            $port = '22483';
            $username = $usuario;
            $password = $pass;

            $dstFile = '/Backups/' . '/userdb_' . date("Y-m-d", strtotime('-37 days')) . '.csv';
            $srcFile = __DIR__ . '/userdb_' . date("Y-m-d", strtotime('-37 days')) . '.csv';

            // Create connection the the remote host
            $conn = ssh2_connect($host, $port);
            ssh2_auth_password($conn, $username, $password);

            // Create SFTP session
            $sftp = ssh2_sftp($conn);

            $sftpStream = fopen('ssh2.sftp://' . $sftp . $dstFile, 'w');

            try {

                if (!$sftpStream) {
                    throw new Exception("Could not open remote file: $dstFile");
                }

                $data_to_send = file_get_contents($srcFile);

                if ($data_to_send === false) {
                    throw new Exception("Could not open local file: $srcFile.");
                }

                if (fwrite($sftpStream, $data_to_send) === false) {
                    throw new Exception("Could not send data from file: $srcFile.");
                }

                fclose($sftpStream);

            } catch (Exception $e) {
                error_log('Exception: ' . $e->getMessage());
                fclose($sftpStream);
            }

            print_r("llego");


            $sql =
                "SELECT usuario_log.usuario_id,
       usuario_log.fecha_crea,
       usuario.fecha_ult,
       usuario_log.usuario_ip
FROM   usuario_log 
     LEFT OUTER JOIN usuario ON (usuario.usuario_id=usuario_log.usuario_id)
         INNER JOIN pais ON (usuario.pais_id = pais.pais_id)
         INNER JOIN usuario_perfil   ON (usuario_perfil.usuario_id=usuario.usuario_id)
WHERE usuario.mandante IN ('14','0','8')
  AND usuario.estado = 'A'
  AND usuario.pais_id IN ('33','46','66')
  AND usuario_perfil.perfil_id = 'USUONLINE'
  AND  usuario_log.fecha_crea >= '$date1'
  AND  usuario_log.fecha_crea <= '$date2'

ORDER BY usuario.usuario_id  ";


            $UsuarioLog = new \Backend\dto\UsuarioLog();

            $UsuarioLogMySqlDAO = new \Backend\mysql\UsuarioLogMySqlDAO();

            $transaccion = $UsuarioMySqlDAO->getTransaction();
            $transaccion->getConnection()->beginTransaction();
            $Usuarios = $BonoInterno->execQuery($transaccion, $sql);
            $Usuarios = json_decode(json_encode($Usuarios), TRUE);


            $users = array();
            $array = array();
            $array["UID"] = 'UID';
            $array["LOGIN"] = 'LOGIN';
            $array["LOGOUT"] = 'LOGOUT';
            $array["IP ADDRESS"] = 'IP ADDRESS';


            array_push($users, $array);
            foreach ($Usuarios as $key => $value) {
                $array = array();
                $array["UID"] = $value["usuario_log.usuario_id"];
                $array["LOGIN"] = $value["usuario_log.fecha_crea"];
                $array["LOGOUT"] = $value["usuario.fecha_ult"];
                $array["IP ADDRESS"] = $value["usuario_log.usuario_ip"];


                array_push($users, $array);
            }


//$users = array_push($users, $keys);

            $json = json_encode($users);

            $jsonans = json_decode($json, 'verdadero');

            $f = fopen(__DIR__ . '/sessions_' . date("Y-m-d", strtotime('-37 days')) . '.csv', 'w');

            foreach ($jsonans as $object) {

                fputcsv($f, $object);
            }

            //fclose( $f );


            $server = 'ds.gaming-curacao.com';
            $usuario = 'vsnetsolnv';
            $pass = '!jHEUNeys8bZ';


            $host = $server;
            $port = '22483';
            $username = $usuario;
            $password = $pass;

            $dstFile = '/Backups/' . '/sessions_' . date("Y-m-d", strtotime('-37 days')) . '.csv';
            $srcFile = __DIR__ . '/sessions_' . date("Y-m-d", strtotime('-37 days')) . '.csv';

            // Create connection the the remote host
            $conn = ssh2_connect($host, $port);
            ssh2_auth_password($conn, $username, $password);

            // Create SFTP session
            $sftp = ssh2_sftp($conn);

            $sftpStream = fopen('ssh2.sftp://' . $sftp . $dstFile, 'w');

            try {

                if (!$sftpStream) {
                    throw new Exception("Could not open remote file: $dstFile");
                }

                $data_to_send = file_get_contents($srcFile);

                if ($data_to_send === false) {
                    throw new Exception("Could not open local file: $srcFile.");
                }

                if (fwrite($sftpStream, $data_to_send) === false) {
                    throw new Exception("Could not send data from file: $srcFile.");
                }

                fclose($sftpStream);

            } catch (Exception $e) {
                error_log('Exception: ' . $e->getMessage());
                fclose($sftpStream);
            }


            print_r("llego");


            $sql =
                "SELECT usuario_historial.externo_id,
       usuario_historial.fecha_crea,
       usuario_historial.usuario_id,
       usuario_historial.movimiento,
       usuario_historial.tipo,
       usuario_historial.valor,
       usuario_historial.creditos_base,
       usuario_historial.tipo,
       usuario.moneda
FROM   usuario_historial 
     LEFT OUTER JOIN usuario ON (usuario.usuario_id=usuario_historial.usuario_id)
         INNER JOIN pais ON (usuario.pais_id = pais.pais_id)
         INNER JOIN usuario_perfil   ON (usuario_perfil.usuario_id=usuario.usuario_id)
WHERE usuario.mandante  IN ('14','0','8')
  AND usuario.estado = 'A'
  AND usuario.pais_id  IN ('33','46','66')
  AND usuario_perfil.perfil_id = 'USUONLINE'
  AND  usuario_historial.fecha_crea >= '$date1'
  AND  usuario_historial.fecha_crea <= '$date2'
ORDER BY usuario.usuario_id 
";


            $UsuarioHistorial = new \Backend\dto\UsuarioHistorial();

            $UsuarioHistorialMySqlDAO = new \Backend\mysql\UsuarioHistorialMySqlDAO();

            $transaccion = $UsuarioHistorialMySqlDAO->getTransaction();
            $transaccion->getConnection()->beginTransaction();
            $Usuarios = $BonoInterno->execQuery($transaccion, $sql);

            $Usuarios = json_decode(json_encode($Usuarios), TRUE);

            $users = array();
            $array = array();
            $array["TransactionID"] = 'TransactionID';
            $array["Date/Time"] = 'Date/Time';
            $array["UID"] = 'UID';
            $array["Description"] = 'Description';
            $array["Debit"] = 'Debit';
            $array["Credit"] = 'Credit';
            $array["Balance"] = 'Balance';
            $array["Currency"] = 'Currency';


            array_push($users, $array);
            foreach ($Usuarios as $key => $value) {
                $array = array();
                $array["TransactionID"] = $value["usuario_historial.externo_id"];
                $array["Date/Time"] = $value["usuario_historial.fecha_crea"];
                $array["UID"] = $value["usuario_historial.usuario_id"];

                switch ($value["usuario_historial.tipo"]) {
                    case 10:
                        $Descripcion = "Deposit";

                        break;

                    case 15:
                        $Descripcion = "balance adjustments";

                        break;
                    case 20:
                        $Descripcion = "Sports bets";
                        break;
                    case 30:
                        $Descripcion = "casino betting";
                        break;
                    case 31:
                        $Descripcion = "Live Casino Betting";
                        break;
                    case 40:
                        $Descripcion = "Withdrawal Note Created";
                        break;
                    case 41:
                        $Descripcion = "Withdrawal Note Paid";
                        break;
                    case 50:
                        $Descripcion = "Bond Redeemed";
                        break;
                    case 60:
                        $Descripcion = "Quota increase";
                        break;
                }

                $array["Description"] = $Descripcion;

                switch ($value["usuario_historial.movimiento"]) {
                    case  "E":
                        $debit = 0;
                        $credit = $value["usuario_historial.valor"];
                        break;
                    case  "S":
                        $debit = $value["usuario_historial.valor"];
                        $credit = 0;
                        break;
                    case  "C":
                        $debit = 0;
                        $credit = $value["usuario_historial.valor"];
                        break;

                }
                $array["Debit"] = $debit;
                $array["Credit"] = $credit;
                $array["Balance"] = $value["usuario_historial.creditos_base"];
                $array["Currency"] = $value["usuario.moneda"];


                array_push($users, $array);
            }


//$users = array_push($users, $keys);

            $json = json_encode($users);

            $jsonans = json_decode($json, 'verdadero');

            $f = fopen(__DIR__ . '/transactions_' . date("Y-m-d", strtotime('-37 days')) . '.csv', 'w');

            foreach ($jsonans as $object) {

                fputcsv($f, $object);
            }

            //fclose( $f );


            //$transaccion->commit();

            $server = 'ds.gaming-curacao.com';
            $usuario = 'vsnetsolnv';
            $pass = '!jHEUNeys8bZ';


            $host = $server;
            $port = '22483';
            $username = $usuario;
            $password = $pass;

            $dstFile = '/Backups/' . '/transactions_' . date("Y-m-d", strtotime('-37 days')) . '.csv';
            $srcFile = __DIR__ . '/transactions_' . date("Y-m-d", strtotime('-37 days')) . '.csv';

            // Create connection the the remote host
            $conn = ssh2_connect($host, $port);
            ssh2_auth_password($conn, $username, $password);

            // Create SFTP session
            $sftp = ssh2_sftp($conn);

            $sftpStream = fopen('ssh2.sftp://' . $sftp . $dstFile, 'w');

            try {

                if (!$sftpStream) {
                    throw new Exception("Could not open remote file: $dstFile");
                }

                $data_to_send = file_get_contents($srcFile);

                if ($data_to_send === false) {
                    throw new Exception("Could not open local file: $srcFile.");
                }

                if (fwrite($sftpStream, $data_to_send) === false) {
                    throw new Exception("Could not send data from file: $srcFile.");
                }

                fclose($sftpStream);

            } catch (Exception $e) {
                error_log('Exception: ' . $e->getMessage());
                fclose($sftpStream);
            }


            print_r("llego");


        } catch (Exception $e) {
            print_r($e);
        }
    }
}