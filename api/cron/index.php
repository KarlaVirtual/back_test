<?php
require(__DIR__ . '/../vendor/autoload.php');

date_default_timezone_set('America/Bogota');

ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');

$arg1 = $argv[1];
if ($arg1 != '') {


    $filename = 'jobs/' . $arg1 . ".php";
    $classname = $arg1;

} else {

    $URI = $_SERVER["REQUEST_URI"];

    $arraySuper = explode("/", current(explode("?", $URI)));
    $arraySuper[oldCount($arraySuper) - 2] = ucfirst($arraySuper[oldCount($arraySuper) - 2]);

    $filename = 'jobs/' . $arraySuper[oldCount($arraySuper) - 1] . ".php";
    $classname = $arraySuper[oldCount($arraySuper) - 1];


}

if (file_exists(__DIR__ . '/' . $filename)) {
    require __DIR__ . '/' . $filename;
    if (class_exists($classname)) {
        try {

            $className = $classname;

            $miObjeto = new $className();  // Crea una instancia de la clase dinámica

            if ($_REQUEST['t'] == 's' || $argv[2] == 's') {
                for ($i = 0; $i <= 10; $i++) {
                    $miObjeto->execute();  // Llamar un método en la instancia
                    sleep(3);
                }
            } elseif ($_REQUEST['t'] == 's05' || $argv[2] == 's05') {
                for ($i = 0; $i <= 5; $i++) {
                    $miObjeto->execute();  // Llamar un método en la instancias6
                    sleep(6);
                }
            } elseif ($_REQUEST['t'] == 's2' || $argv[2] == 's2') {
                for ($i = 0; $i <= 19; $i++) {
                    $miObjeto->execute();  // Llamar un método en la instancias6
                    sleep(3);
                }
            } elseif ($_REQUEST['t'] == 's4' || $argv[2] == 's4') {
                for ($i = 0; $i <= 39; $i++) {
                    $miObjeto->execute();  // Llamar un método en la instancias6
                    sleep(3);
                }
            } elseif ($_REQUEST['t'] == 's6' || $argv[2] == 's6') {
                for ($i = 0; $i <= 59; $i++) {
                    $miObjeto->execute();  // Llamar un método en la instancias6
                    sleep(1);
                }
            } elseif ($_REQUEST['t'] == 'bs' || $argv[2] == 'bs') {
                for ($i = 0; $i <= 10; $i++) {

                    exec('/usr/bin/php -f ' . __DIR__ . '/index.php ' . $arg1 . ' > /dev/null &'); // Llamar un método en la instancia

                    sleep(3);
                }
            } elseif ($_REQUEST['t'] == 'bs2' || $argv[2] == 'bs2') {
                for ($i = 0; $i <= 19; $i++) {
                    exec('/usr/bin/php -f ' . __DIR__ . '/index.php ' . $arg1 . ' > /dev/null &'); // Llamar un método en la instancia
                    sleep(3);
                }
            } elseif ($_REQUEST['t'] == 'bs4' || $argv[2] == 'bs4') {
                for ($i = 0; $i <= 39; $i++) {
                    exec('/usr/bin/php -f ' . __DIR__ . '/index.php ' . $arg1 . ' > /dev/null &'); // Llamar un método en la instancia
                    sleep(3);
                }
            } elseif ($_REQUEST['t'] == 'bs6' || $argv[2] == 'bs6') {
                for ($i = 0; $i <= 59; $i++) {
                    exec('/usr/bin/php -f ' . __DIR__ . '/index.php ' . $arg1 . ' > /dev/null &'); // Llamar un método en la instancia
                    sleep(1);
                }
            } else {
                $miObjeto->execute();  // Llamar un método en la instancia
            }
            print_r('OK');

        } catch (Exception $e) {
            print_r($e);
            print_r('ERROR');

        }
    } else {
        print_r('ERROR');

    }

}
