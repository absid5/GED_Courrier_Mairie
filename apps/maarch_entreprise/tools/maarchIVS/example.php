<?php

$time = microtime(true);

require_once 'MaarchIVS.php';

$started = MaarchIVS::start(__DIR__ . DIRECTORY_SEPARATOR . 'conf' . DIRECTORY_SEPARATOR . 'conf.xml', 'xml');
$valid = MaarchIVS::run('silent');

if (!$valid) {
    var_dump(MaarchIVS::debug());
} else {
    echo "Request is valid";
}

var_dump(microtime(true) - $time);
