<?php

require_once "core/class/class_request.php";
require_once "modules/folder/class/class_modules_tools.php";
$core = new core_tools();

$core->load_lang();
$core->test_admin('create_folder', 'folder');
$folder = new folder();
$folder->create_folder($_GET['iframe']);
