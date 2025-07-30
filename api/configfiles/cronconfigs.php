<?php


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
$final = array(
    'token' => 'D0radobet1234!'
);
$payload = encrypt(json_encode(($final)));
$ch = curl_init('http://admin3.local/configfiles/getconfig.php?getlastfiles=1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_TIMEOUT, 20);

// Set HTTP Header for POST request
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($payload))
);
//$rs = curl_exec($ch);
$result = (curl_exec($ch));

$result = explode(',',$result);

foreach ($result as $item) {

    $item = explode('_',$item);
    $final = array(
        'token' => 'D0radobet1234!',
        'partner' => $item[0],
        'country' => $item[1],
        'category' => $item[2],
        'mobile' => $item[3],
    );

    $payload = encrypt(json_encode(($final)));
    $ch = curl_init('http://admin3.local/configfiles/getconfig.php');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);

// Set HTTP Header for POST request
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload))
    );
//$rs = curl_exec($ch);
    $result = (curl_exec($ch));

    $filename = $item[0] . '_' . $item[1] . '_' . $item[2]. '_' . $item[3];

    if ($result != '') {
        $log = "\r\n" . date("Y-m-d H:i:s") . "-----------ANTERIOR--------------" . "\r\n";
        $log = $log . file_get_contents('../files/' . $filename);
//Save string to log, use FILE_APPEND to append.
        $log = $log . '----';

         fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

        file_put_contents('/home/home2/backend/api/configfiles/files/' . $filename, ($result));
    }

    exec('chown -R backend:backend /home/home2/backend/api/configfiles/files/');

}

