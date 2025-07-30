<?php
error_reporting(E_ALL);
ini_set('display_errors', 'ON');

$string = '';

$string = (file_get_contents('php://input'));

$stringexplode =explode("REQUEST: ",$string);
try{
if(false) {
    foreach ($stringexplode as $item) {

        foreach (explode("</PKT>", $item) as $item2) {
            $item2 = $item2 . '</PKT>';

            if (strpos($item2, 'Method Name="PlaceBet"') && false) {
                print_r($item2);



            }
            if (strpos($item2, 'Method Name="AwardWinnings"') ) {
                print_r($item2);


            }
            if (strpos($item2, 'Method Name="CashoutBet"')&& false) {

                print_r($item2);


            }
            if (strpos($item2, 'Method Name="RefundBet"')&& false ) {
                print_r($item2);


            }

            if (strpos($item2, 'Method Name="LossSignal"') && false) {
                print_r("item2");


            }

        }
    }
}else{
    foreach ($stringexplode as $item) {

        foreach (explode("</PKT>", $item) as $item2) {
            $item2 = $item2 . '</PKT>';

            if (strpos($item2, 'Method Name="PlaceBet"')) {
                print_r($item2);
                $ch = curl_init("https://doradobet.com/api/");
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $item2);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 300);
                curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

// Set HTTP Header for POST request
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/xml')
                );

//$rs = curl_exec($ch);
                $result = (curl_exec($ch));

                // Close cURL session handle
                curl_close($ch);
                print_r($result);


                $log = "\r\n" . "-----------REQUEST--------------" . "\r\n";
                $log = $log . $item2;
                //Save string to log, use FILE_APPEND to append.

                $log = $log . "\r\n" . "-----------RESPONSE--------------" . "\r\n";
                $log = $log . $result;

                  fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

                sleep(0.5);

            }
            if (strpos($item2, 'Method Name="AwardWinnings"')) {
                print_r($item2);
                $ch = curl_init("https://doradobet.com/api/");
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $item2);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 300);
                curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

// Set HTTP Header for POST request
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/xml')
                );

//$rs = curl_exec($ch);
                $result = (curl_exec($ch));

                // Close cURL session handle
                curl_close($ch);
                print_r($result);


                $log = "\r\n" . "-----------REQUEST--------------" . "\r\n";
                $log = $log . $item2;
                //Save string to log, use FILE_APPEND to append.

                $log = $log . "\r\n" . "-----------RESPONSE--------------" . "\r\n";
                $log = $log . $result;

                  fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

                sleep(0.5);

            }
            if (strpos($item2, 'Method Name="CashoutBet"')) {
                print_r("item2");
                $ch = curl_init("https://doradobet.com/api/");
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $item2);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 300);
                curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

// Set HTTP Header for POST request
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/xml')
                );

//$rs = curl_exec($ch);
                $result = (curl_exec($ch));

                // Close cURL session handle
                curl_close($ch);
                print_r($result);


                $log = "\r\n" . "-----------REQUEST--------------" . "\r\n";
                $log = $log . $item2;
                //Save string to log, use FILE_APPEND to append.

                $log = $log . "\r\n" . "-----------RESPONSE--------------" . "\r\n";
                $log = $log . $result;

                  fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

                sleep(0.5);


            }
            if (strpos($item2, 'Method Name="RefundBet"')) {
                print_r("item2");
                $ch = curl_init("https://doradobet.com/api/");
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $item2);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 300);
                curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

// Set HTTP Header for POST request
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/xml')
                );

//$rs = curl_exec($ch);
                $result = (curl_exec($ch));

                // Close cURL session handle
                curl_close($ch);
                print_r($result);


                $log = "\r\n" . "-----------REQUEST--------------" . "\r\n";
                $log = $log . $item2;
                //Save string to log, use FILE_APPEND to append.

                $log = $log . "\r\n" . "-----------RESPONSE--------------" . "\r\n";
                $log = $log . $result;

                  fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

                sleep(0.5);


            }

            if (strpos($item2, 'Method Name="LossSignal"')) {
                print_r("item2");
                $ch = curl_init("https://doradobet.com/api/");
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $item2);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 300);
                curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

// Set HTTP Header for POST request
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Content-Type: application/xml')
                );

//$rs = curl_exec($ch);
                $result = (curl_exec($ch));

                // Close cURL session handle
                curl_close($ch);
                print_r($result);


                $log = "\r\n" . "-----------REQUEST--------------" . "\r\n";
                $log = $log . $item2;
                //Save string to log, use FILE_APPEND to append.

                $log = $log . "\r\n" . "-----------RESPONSE--------------" . "\r\n";
                $log = $log . $result;

                  fwriteCustom('log_' . date("Y-m-d") . '.log',$log);

                sleep(0.5);


            }

        }
    }
}
}catch (Exception $e){
    print_r($e);
}
