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
use Backend\dto\UsuarioLog2;
use Backend\dto\UsuarioMandante;
use Backend\dto\ConfigurationEnvironment;
use Backend\dto\TransaccionApi;
use Backend\dto\LealtadInterna;
use Backend\dto\TransaccionJuego;
use Backend\dto\TransjuegoLog;
use Backend\dto\UsuarioAutomation;
use Backend\dto\UsuarioHistorial;
use Backend\mysql\BonoDetalleMySqlDAO;
use Backend\mysql\UsuarioLog2MySqlDAO;
use Backend\mysql\UsuarioMySqlDAO;
use Backend\mysql\TransaccionJuegoMySqlDAO;
use Backend\mysql\TransjuegoLogMySqlDAO;
use Backend\mysql\UsuarioAutomationMySqlDAO;
use Backend\mysql\UsuarioHistorialMySqlDAO;


ini_set('display_errors', 'OFF');
date_default_timezone_set('America/Lima');
require_once __DIR__ . '../../vendor/autoload.php';
///home/devadmin/api/api/
header('Content-Type: application/json');
ini_set('memory_limit', '-1');
ini_set('display_errors', 'OFF');

    $sql = "SELECT  uv.usuverificacion_id,uv.fecha_crea,uv.usuario_id,
            vl.json
            FROM usuario_verificacion uv
            JOIN clasificador c ON uv.clasificador_id=c.clasificador_id
            JOIN verificacion_log vl ON uv.usuverificacion_id = vl.usuverificacion_id
            WHERE 1=1
            AND uv.clasificador_id=699
            AND uv.estado='A'
            GROUP BY uv.usuverificacion_id
            ORDER BY uv.usuverificacion_id DESC
;";
print_r($sql);
$BonoInterno = new BonoInterno();
$UsuarioVerificacion = new \Backend\dto\UsuarioVerificacion();


$Usuario = new \Backend\dto\Usuario();

$UsuarioMySqlDAO = new \Backend\mysql\UsuarioMySqlDAO();
//print_r('paso');

$Verificaciones = $BonoInterno->execQuery('', $sql);
$Verificaciones = json_decode(json_encode($Verificaciones), TRUE);
//print_r($Verificaciones);

foreach ($Verificaciones as $key => $value) {

    print_r('paso'.$key);
    $BonoInternoMySqlDAO = new \Backend\mysql\BonoInternoMySqlDAO();
    $transaccion = $BonoInternoMySqlDAO->getTransaction();
    $Json = strval($value["vl.json"]);
    $usuarioId = strval($value["uv.usuario_id"]);

        $Usuario = new Usuario($usuarioId);

    $message='*Paso:* - *Usuario:* ' . $Usuario->usuarioId . ' - *ID:* ' .$value["uv.usuverificacion_id"] . ' - *Fecha:* ' .$value["uv.fecha_crea"] ;

    exec("php -f /home/home2/backend/api/src/imports/Slack/message.php '" . $message . "' '#dev2' > /dev/null & ");

    //print_r($Usuario);
    //print_r('antesJSON');


    if($Usuario != null){


        if($Usuario->paisId == 66 && $Usuario->mandante == 8){
            $clientAPI='7820d63a-fa8e-4ca9-a15e-29167250bc01';
            $password='ViB41kdMxNymbuSSN8t22xtosH1VpW1J';
            $clientId='4tcvesfugioskgh9fk1rbn40q9';
            $clientSecret='oek23uu3b4fs942b3g91vbe52amru6a4sip4rtkhtlthg3q0cn3';
        }

        if($Usuario->paisId == 173 && $Usuario->mandante == 0){
            $clientAPI='dbd48732-31e3-46a6-9cff-a53f4a7fb466';
            $password='GAcItu4c6obm4SPAiwH4a0nfm7F2Ghmf';
            $clientId='4bh16jfeb25cd339255vhobe3v';
            $clientSecret='ba1pdtomr8fb6mtn0uqq4m9pjvrvf6q812gf3qf2jenohvrc75a';
        }

        if($Usuario->paisId == 2 && $Usuario->mandante == 0){
            $clientAPI='ab7804a5-bcd7-4691-b780-2b7b2f7ef842';
            $password='PnC2DTcTNfujrs9gKaGura5nHMN73AxE';
            $clientId='3odek9asnmqbhnffjrn5cuh7sj';
            $clientSecret='ohfvbrl7r32m7kcrbjsej62lopevk8d9jaetf7dluu9jctlu3i0';
        }

        if($Usuario->paisId == 66 && $Usuario->mandante == 0){
            $clientAPI='2d7fd5e8-cb6e-490c-9123-610040b07080';
            $password='aAoaKl2gfdFG3nHLvX7dbHDdB0jawAab';
            $clientId='2r9vih8167a4nannachqc25ikd';
            $clientSecret='k3d452salljigbs49dhrh6m98nato67uj3llremq1g14dfabj2h';
        }

        if($Usuario->paisId == 46 && $Usuario->mandante == 0){
            $clientAPI='2f67c75b-1eb0-4467-84f4-e2c8e8d00f85';
            $password='Umlp6YNpTFohjY6mSueTcHp5BSL6m4s9';
            $clientId='2o55kejncoucrl2he94qmqqjga';
            $clientSecret='92p0pfgc8h132hpdp0odna4eaf0523d3g30qqvf376jgtcpf3m4';
        }

        if($Usuario->paisId == 33 && $Usuario->mandante == 14){
            $clientAPI='14af660d-affa-422b-a535-c9d5908097d2';
            $password='SjWkl5RwgDQxBeyTWZZ1erbymBhkvMKB';
            $clientId='250fec8rg6shrtbiqjmc1bvisk';
            $clientSecret='d2gn9dfme4osnidcamqi6st4ubpnfrkhotnkte0tpmnu0mkhkg2';
        }

        if($Usuario->paisId == 33 && $Usuario->mandante == 0){
            $clientAPI='7f0800bb-e489-4b83-9857-d2a7d2365daf';
            $password='43peUnLNKTeZZChRz5HAWBGuPOqSZQJ8';
            $clientId='cctso5nd43s6l99utt5vi6ns5';
            $clientSecret='8udh35bg5e19i9eie8mrfil5m0836m21mg7poca44qra8tafebk';
        }


        if($Usuario->paisId == 146 && $Usuario->mandante == 13){
            $clientAPI='fc722778-11c1-41e1-ae6a-9dfaa38698aa';
            $password='BqXw54PF6dtfkbKAFoBTPnLZT2bOCVDA';
            $clientId='1gb0s8b2ckceg7hp0p41bakluf';
            $clientSecret='dleaahsdmefe421e89c7j6m6tvd3en8bnip9iq9dnr12mv35mtn';
        }
        if($Usuario->paisId == 94 && $Usuario->mandante == 0){
            $clientAPI='4024abf9-56bf-4b07-8db7-81b6198a95c3';
            $password='3666IqjxWH3QoHRifK1wydb4jhSJXGIZ';
            $clientId='6d6kha6ncgn282fjucm8f0a3pq';
            $clientSecret='1on3jsdt49q92sg0kpdgis8n1je4mnk8qe6dstrv7jvg3iq3be4g';
        }

        if($Usuario->paisId == 173 && $Usuario->mandante == 18){
            $clientAPI='30f363d5-c846-4f44-ab90-fc95c341c21a';
            $password='1CvmZNKiTaRsnOLR4XbqhrYDMtnpVUyA';
            $clientId='49k67tn72h2npor7r53ekkui91';
            $clientSecret='ne4msuoef05uqp4ldm292umc4qcih3o2govhp28rlfdc7f0l5dl';
        }

        if($Usuario->paisId == 146 && $Usuario->mandante == 18){
            $clientAPI='5e049cb4-1ec6-4767-82bf-13bd52ac7554';
            $password='wQa6Rw0beko8wgr5osQVjKt75NUD2HDF';
            $clientId='159qscitbnu1l86tj237h5svp0';
            $clientSecret='5n3h5acd65eenh0o72n3hcs4h54gplg197sad6fepfrcfkrqcgo';
        }

        if ($Usuario->paisId == 68 && $Usuario->mandante == 0) {
            $clientAPI = 'af4bf3ad-87c7-4be0-ba1a-a8bf3d8dff57';
            $password = 'K3jlKaa7aWH1T1ZCXBetMWJs8Rc0IvEd';
            $clientId = '5811deehobm92v58ruld7upe02';
            $clientSecret = '11koj1irafoqtg3gbujb563vi4ee0b4m0q6chsnbkan3r6i4phvv';
        }
    }



    //print_r($Json);

        $Json = json_decode($Json);
    $idLogA=0;
    $idLogP=0;
    //print_r($Json);



    $DocumentJumio = $Json->capabilities->extraction[0]->data->documentNumber;
    $Registro = new \Backend\dto\Registro("",$Usuario->usuarioId);

    if ($DocumentJumio != "") {
        if (is_array($DocumentJumio)) {
            $DocumentJumio = implode($DocumentJumio);
        }
        $UsuarioLog = new UsuarioLog2();
        $UsuarioLog->setUsuarioId($Usuario->usuarioId);
        $UsuarioLog->setUsuarioIp('');
        $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
        $UsuarioLog->setUsuariosolicitaIp("");
        $UsuarioLog->setTipo("USUCEDULA");
        $UsuarioLog->setEstado("A");
        $UsuarioLog->setValorAntes($Registro->getCedula());
        $UsuarioLog->setValorDespues($DocumentJumio);
        $UsuarioLog->setUsucreaId(0);
        $UsuarioLog->setUsumodifId(0);
        $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
        $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($transaccion);
        $UsuarioLogMySqlDAO2->insert($UsuarioLog);

    }

    $Registro->setCedula($DocumentJumio);


    $RegistroMySqlDAO = new \Backend\mysql\RegistroMySqlDAO();
    $RegistroMySqlDAO = new $RegistroMySqlDAO($transaccion);
    $RegistroMySqlDAO->update($Registro);
    $transaccion->commit();
    $transaccion = $BonoInternoMySqlDAO->getTransaction();



    foreach ($Json->credentials as $key1 => $value1) {


        foreach ($value1->parts as $key => $value) {
            //print_r($value);

            if ($value->classifier == "FRONT") {

                $tipo = 'USUDNIANTERIOR';

                $Auth = base64_encode( $clientAPI . ":" . $password);

                $ch = curl_init($value->href);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 300);
                curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                //curl_setopt($ch, CURLOPT_USERPWD, $clientAPI . ":" . $password);

                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","Accept: application/json", "Authorization: Basic ".$Auth));


                $result = (curl_exec($ch));

                $Imagen = $result;

                $file_contents1 = addslashes($Imagen);


                $UsuarioLog = new UsuarioLog2();
                $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                $UsuarioLog->setUsuarioIp("");
                $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                $UsuarioLog->setUsuariosolicitaIp("");
                $UsuarioLog->setUsuarioaprobarId(0);
                $UsuarioLog->setTipo($tipo);
                $UsuarioLog->setEstado("A");
                $UsuarioLog->setValorAntes('');
                $UsuarioLog->setValorDespues('');
                $UsuarioLog->setUsucreaId(0);
                $UsuarioLog->setUsumodifId(0);
                $UsuarioLog->setImagen($file_contents1);
                $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());

                $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($transaccion);

                $idLogA= $UsuarioLogMySqlDAO2->insert($UsuarioLog);

                $data = $Imagen;
                $filename = "c" . $UsuarioLog->usuarioId;

                $filename = $filename . 'A';

                $filename = $filename . '.png';

                if (!file_exists('/home/home2/backend/images/c/')) {
                    mkdir('/home/home2/backend/images/c/', 0755, true);
                }

                $dirsave = '/home/home2/backend/images/c/' . $filename;
                file_put_contents($dirsave, $data);

                shell_exec('export BOTO_CONFIG=/home/backend/.config/gcloud/legacy_credentials/admin@bpoconsultores.com.co/.boto && export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/home/backend/google-cloud-sdk/bin && gsutil -h "Cache-Control:public,max-age=604800" cp '.$dirsave.' gs://cedulas-1/c/');

                //print_r('export BOTO_CONFIG=/home/backend/.config/gcloud/legacy_credentials/admin@bpoconsultores.com.co/.boto && export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/home/backend/google-cloud-sdk/bin && gsutil -h "Cache-Control:public,max-age=604800" cp '.$dirsave.' gs://cedulas-1/c/');

            }

            if ($value->classifier == "BACK") {
                $tipo = 'USUDNIPOSTERIOR';
                //$file_contents1  = file_get_contents($value->href);

                $Auth = base64_encode( $clientAPI . ":" . $password);

                $ch = curl_init($value->href);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 300);
                curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                //curl_setopt($ch, CURLOPT_USERPWD, $clientAPI . ":" . $password);

                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","Accept: application/json", "Authorization: Basic ".$Auth));


                $result = (curl_exec($ch));

                $Imagen = $result;

                $file_contents1 = addslashes($Imagen);
                $UsuarioLog = new UsuarioLog2();
                $UsuarioLog->setUsuarioId($Usuario->usuarioId);
                $UsuarioLog->setUsuarioIp("");
                $UsuarioLog->setUsuariosolicitaId($Usuario->usuarioId);
                $UsuarioLog->setUsuariosolicitaIp("");
                $UsuarioLog->setUsuarioaprobarId(0);
                $UsuarioLog->setTipo($tipo);
                $UsuarioLog->setEstado("A");
                $UsuarioLog->setValorAntes('');
                $UsuarioLog->setValorDespues('');
                $UsuarioLog->setUsucreaId(0);
                $UsuarioLog->setUsumodifId(0);
                $UsuarioLog->setImagen($file_contents1);
                $UsuarioLog->setSversion($UsuarioVerificacion->getUsuverificacionId());
                $UsuarioLogMySqlDAO2 = new UsuarioLog2MySqlDAO($transaccion);

                $idLogP= $UsuarioLogMySqlDAO2->insert($UsuarioLog);


                $data = $Imagen;
                $filename = "c" . $UsuarioLog->usuarioId;

                $filename = $filename . 'P';

                $filename = $filename . '.png';

                if (!file_exists('/home/home2/backend/images/c/')) {
                    mkdir('/home/home2/backend/images/c/', 0755, true);
                }

                $dirsave = '/home/home2/backend/images/c/' . $filename;
                file_put_contents($dirsave, $data);

                shell_exec('export BOTO_CONFIG=/home/backend/.config/gcloud/legacy_credentials/admin@bpoconsultores.com.co/.boto && export PATH=/sbin:/bin:/usr/sbin:/usr/bin:/home/backend/google-cloud-sdk/bin && gsutil -h "Cache-Control:public,max-age=604800" cp '.$dirsave.' gs://cedulas-1/c/');

            }


        }
    }

    $transaccion->commit();

}




print_r("exito");




