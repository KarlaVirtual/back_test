<?php
/**
* Send WS Usuario
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/

use Backend\dto\Proveedor;
use Backend\dto\UsuarioMandante;
use Backend\dto\UsuarioSession;
use Backend\dto\UsuarioToken;
use Backend\dto\Usuario;
use Backend\mysql\UsuarioLogMySqlDAO;
use Backend\websocket\WebsocketUsuario;
require_once __DIR__ . '../../vendor/autoload.php';

ini_set("display_errors","ON");
error_reporting(E_ALL);

$id=$_REQUEST['i'];
$idm=$_REQUEST['im'];
$m=$_REQUEST['m'];
$m = str_replace(" ", "+", $m);
$id = str_replace(" ", "+", $id);
$idm = str_replace(" ", "+", $idm);

syslog(LOG_WARNING, "SENDWSUSUARIO REQUEST:" . json_encode($_REQUEST));
if($_REQUEST["testing"]==1){
    $id = decrypt($id);
    $idm = decrypt($idm);
    $m =decrypt($m);
    print_r($id);
    print_r($idm);
    print_r($m);
    $idm="886";
    $id="";

    $data = json_decode($m, true);


    if($id != ""){

        $UsuarioMandante = new UsuarioMandante($id);

    }


    if($idm != ""){

        $Usuario = new Usuario($idm);
        $UsuarioMandante = new UsuarioMandante("",$Usuario->usuarioId,$Usuario->mandante);

    }


    if(in_array($UsuarioMandante->mandante,array('0',8,6,2))){

        $dataSend = $data;
        $WebsocketUsuario = new WebsocketUsuario('','');
        $WebsocketUsuario->sendWSPieSocket($UsuarioMandante,$dataSend);

    }

    exit();
}

if(($id != '' || $idm != '') && $m != '') {

    $id = decrypt($id);
    $idm = decrypt($idm);
    $m =decrypt($m);

    if((is_numeric($id) || is_numeric($idm) ) && $m != '') {

        $data = json_decode($m, true);

        $UsuarioSession = new UsuarioSession();
        $rules = [];

        array_push($rules, array("field" => "usuario_session.estado", "data" => "A", "op" => "eq"));

        if($id != ""){
            array_push($rules, array("field" => "usuario_session.usuario_id", "data" => $id, "op" => "eq"));

        }


        if($idm != ""){
            array_push($rules, array("field" => "usuario_mandante.usuario_mandante", "data" => $idm, "op" => "eq"));

        }


        $filtro = array("rules" => $rules, "groupOp" => "AND");
        $json = json_encode($filtro);

/*
        $usuarios = $UsuarioSession->getUsuariosCustom("usuario_session.*", "usuario_session.ususession_id", "asc", 0, 100, $json, true);

        $usuarios = json_decode($usuarios);

        $usuariosFinal = [];

        foreach ($usuarios->data as $key => $value) {

            $WebsocketUsuario = new WebsocketUsuario($value->{'usuario_session.request_id'}, $data);
            $WebsocketUsuario->sendWSMessage();

        }*/

        if($id != ""){

            $UsuarioMandante = new UsuarioMandante($id);

        }


        if($idm != ""){

            $Usuario = new Usuario($idm);
            $UsuarioMandante = new UsuarioMandante("",$Usuario->usuarioId,$Usuario->mandante);

        }


        if(in_array($UsuarioMandante->mandante,array('0',8,6,2))){
            $dataSend = $data;
            $WebsocketUsuario = new WebsocketUsuario('','');
            $WebsocketUsuario->sendWSPieSocket($UsuarioMandante,$dataSend);

        }
    }
}
//print_r(encrypt(16));
//print_r(" EJMPL ");
//print_r(encrypt('{"messages":[{"body":"¡ Bien :thumbsup: ! Sumaste '. "1".' puntos en '."2".' :clap:"}]}'));
function encrypt($data, $encryption_key = "")
{
    $passEncryt ='li1296-151.members.linode.com|3232279913';
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-128-CTR'));
    $encrypted_string = bin2hex($iv) . openssl_encrypt($data, 'AES-128-CTR', $passEncryt, 0, $iv);
    return $encrypted_string;
}

function decrypt($data, $encryption_key = "")
{
    $passEncryt ='li1296-151.members.linode.com|3232279913';

    if($data == "a17627d4cddfa7086c831da71e08701fMiw%209oHr3wpioQ%3D%3D" || $data == "a17627d4cddfa7086c831da71e08701fMiw 9oHr3wpioQ=="  || $data == "a17627d4cddfa7086c831da71e08701fMiw 9oHr3wpioQ%3D%3D" ){
        $data="73543a712cc0221442390a0d05f508ebYV5AXzWRDutCdw==";

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

