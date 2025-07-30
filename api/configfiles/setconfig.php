<?php
ini_set("display_errors", "OFF");
$body = decrypt(file_get_contents('php://input'));

$data = json_decode($body);
$log = "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
$log = $log . json_encode($data);
//Save string to log, use FILE_APPEND to append.

fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

if ($data->token == 'D0radobet1234!') {
    $log = "\r\n" . date("Y-m-d H:i:s") . "-------------------------" . "\r\n";
    $log = $log . json_encode($data);
//Save string to log, use FILE_APPEND to append.

    fwriteCustom('log_' . date("Y-m-d") . '.log',$log);


    $filename = $data->partner . '_' . $data->country . '_' . $data->category. '_' . $data->mobile;

    if ($data->content != '') {
        $log = "\r\n" . date("Y-m-d H:i:s") . "-----------ANTERIOR--------------" . "\r\n";
        $log = $log . file_get_contents('../files/' . $filename);
//Save string to log, use FILE_APPEND to append.
        $log = $log . '----';

         fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

        file_put_contents('files/' . $filename, ($data->content));
    }
};
print_r('OK');


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
