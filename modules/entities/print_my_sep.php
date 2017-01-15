<?php

require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Type: application/pdf");
header("Content-Disposition: inline; filename=".basename('maarch.'.strtoupper($format)).";");
header("Content-Transfer-Encoding: binary");
readfile($_SESSION['config']['tmppath'].DIRECTORY_SEPARATOR.$_SESSION['user']['UserId'].".PDF");
