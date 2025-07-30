<?php
/**
* Procesar usuarios
*   
* 
* @package ninguno 
* @author Daniel Tamayo <it@virtualsoft.tech>
* @version ninguna
* @access public 
* @see no
* 
*/
error_reporting(E_ALL);
ini_set("display_errors","ON");

require_once __DIR__ . '../../vendor/autoload.php';
use Backend\dto\UsuarioMandante;
use Backend\mysql\UsuarioMandanteMySqlDAO;


$string="2310,12878,13554,13562,13566,13586,13589,13592,13593,13597,13598,13601,13608,13609,13626,13653,13658,13661,13664,13672,13675,13699,13729,13731,13738,13807,13874,13909,13915,13916,13920,13925,13931,13938,13943,13949,13971,13978,13980,13981,13993,13995,13996,13999,14002,14018,14026,14043,14049,14061,14082,14083,14089,14094,14130,14152,14155,14159,14161,14165,14169,14174,14192,14194,14203,14207,14209,14211,14223,14226,14246,14267,14272,14283,14287,14292,14293,14294,14299,14302,14304,14306,14307,14310,14311,14317,14320,14326,14335,14337,14353,14364,14368,14372";
$arr = explode(",",$string);
foreach ($arr as $item) {
    print_r($item);
    $item = intval($item);


    try {
        print_r("ENTRA");

        $UsuarioMandante = new \Backend\dto\UsuarioMandante("", $item, 0);
        print_r($UsuarioMandante);

        if($UsuarioMandante->usumandanteId == ""){
            $Usuario = new \Backend\dto\Usuario($item);

            $UsuarioMandante = new \Backend\dto\UsuarioMandante();

            $UsuarioMandante->mandante = $Usuario->mandante;
            //$UsuarioMandante->dirIp = $dir_ip;
            $UsuarioMandante->nombres = $Usuario->nombre;
            $UsuarioMandante->apellidos = $Usuario->nombre;
            $UsuarioMandante->estado = 'A';
            $UsuarioMandante->email = $Usuario->login;
            $UsuarioMandante->moneda = $Usuario->moneda;
            $UsuarioMandante->paisId = $Usuario->paisId;
            $UsuarioMandante->saldo = 0;
            $UsuarioMandante->usuarioMandante = $Usuario->usuarioId;
            $UsuarioMandante->usucreaId = 0;
            $UsuarioMandante->usumodifId = 0;
            $UsuarioMandante->propio = 'S';

            $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO();
            $usuario_id = $UsuarioMandanteMySqlDAO->insert($UsuarioMandante);

            $UsuarioMandanteMySqlDAO->getTransaction()->getConnection()->commit();


        }

    } catch (Exception $e) {
        print_r($e);

        if ($e->getCode() == 22) {
            print_r($item);
            $Usuario = new \Backend\dto\Usuario($item);

            $UsuarioMandante = new \Backend\dto\UsuarioMandante();

            $UsuarioMandante->mandante = $Usuario->mandante;
            //$UsuarioMandante->dirIp = $dir_ip;
            $UsuarioMandante->nombres = $Usuario->nombre;
            $UsuarioMandante->apellidos = $Usuario->nombre;
            $UsuarioMandante->estado = 'A';
            $UsuarioMandante->email = $Usuario->login;
            $UsuarioMandante->moneda = $Usuario->moneda;
            $UsuarioMandante->paisId = $Usuario->paisId;
            $UsuarioMandante->saldo = 0;
            $UsuarioMandante->usuarioMandante = $Usuario->usuarioId;
            $UsuarioMandante->usucreaId = 0;
            $UsuarioMandante->usumodifId = 0;
            $UsuarioMandante->propio = 'S';

            $UsuarioMandanteMySqlDAO = new UsuarioMandanteMySqlDAO();
            $usuario_id = $UsuarioMandanteMySqlDAO->insert($UsuarioMandante);

            $UsuarioMandanteMySqlDAO->getTransaction()->getConnection()->commit();


        }
    }

}