<?php

$body = decrypt(file_get_contents('php://input'));

$data = json_decode($body);

if($data->token = 'D0radobet1234!') {

    if($_REQUEST['getlastfiles']=='1'){

        exec('find /home/home2/backend/api/configfiles/files/ -mtime -0.007', $resultfiles);
        $arrayfinal=array();
        foreach ($resultfiles as $resultfile) {
            $resultfile = explode('/files/',$resultfile)[1];
            array_push($arrayfinal,$resultfile);
        }
        print_r(implode(',',$arrayfinal));


    }else{

        $filename = $data->partner . '_' . $data->country . '_' . $data->category. '_' . $data->mobile;

        $setting = file_get_contents(__DIR__ . '/files/'.$filename);

        print_r($setting);
    }

}





function decrypt($data, $encryption_key = "")
{
    $data = str_replace("vSfTp", "/", $data);

    $passEncryt = 'li1296-151.members.linode.com|3232279913';

    $iv_strlen = 2 * openssl_cipher_iv_length('AES-128-CTR');
    if (preg_match("/^(.{" . $iv_strlen . "})(.+)$/", $data, $regs)) {
        list(, $iv, $crypted_string) = $regs;
        $decrypted_string = openssl_decrypt($crypted_string, 'AES-128-CTR', $passEncryt, 0, hex2bin($iv));
        return $decrypted_string;
    } else {
        return FALSE;
    }
}

function encrypt($data, $encryption_key = "")
{
    $passEncryt = 'li1296-151.members.linode.com|3232279913';
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-128-CTR'));
    $encrypted_string = bin2hex($iv) . openssl_encrypt($data, 'AES-128-CTR', $passEncryt, 0, $iv);
    $encrypted_string = str_replace("/", "vSfTp", $encrypted_string);
    return $encrypted_string;
}
