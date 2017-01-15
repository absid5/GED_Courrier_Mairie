<?php
include_once('../../core/init.php');
function compress($buffer)
{
    /* remove comments */
    $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
    /* remove tabs, spaces, newlines, etc. */
    $buffer = str_replace(
        array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer
    );
    $buffer = preg_replace('! ?([:}{,;]) ?!', '$1', $buffer);
    return $buffer;
}

$date = mktime(0, 0, 0, date("m") + 2 , date("d"), date("Y"));
$date = date("D, d M Y H:i:s", $date);
$time = 30 * 12 * 60 * 60;
header("Pragma: public");
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
//header("Expires: ".$date." GMT");
//header("Cache-Control: max-age=".$time.", must-revalidate");
header('Content-type: text/css; charset=utf-8');

ob_start("compress");

if (isset($_GET['ie']) && file_exists('apps/' . $_SESSION['config']['app_id'] . '/css/style_ie.css')) {
    include 'apps/' . $_SESSION['config']['app_id'] . '/css/style_ie.css';
    foreach (array_keys($_SESSION['modules_loaded']) as $value) {
        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR
            . 'modules' . DIRECTORY_SEPARATOR
            . $_SESSION['modules_loaded'][$value]['name']
            . DIRECTORY_SEPARATOR . "css" . DIRECTORY_SEPARATOR
            . "module.css"
        ) || file_exists(
            $_SESSION['config']['corepath'] . 'modules'
            . DIRECTORY_SEPARATOR
            . $_SESSION['modules_loaded'][$value]['name']
            . DIRECTORY_SEPARATOR . "css" . DIRECTORY_SEPARATOR
            . "module.css"
        )
        ) {
            echo 'modules/' . $_SESSION['modules_loaded'][$value]['name']
                . '/css/module.css';
            include 'modules/' . $_SESSION['modules_loaded'][$value]['name']
                . '/css/module.css';
        }
    }
} else if (isset($_GET['ie7']) && file_exists('apps/' . $_SESSION['config']['app_id'] . '/css/style_ie7.css')) {
    include 'apps/' . $_SESSION['config']['app_id'] . '/css/style_ie7.css';
    foreach (array_keys($_SESSION['modules_loaded']) as $value) {
        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'modules'
            . DIRECTORY_SEPARATOR . $_SESSION['modules_loaded'][$value]['name']
            . DIRECTORY_SEPARATOR . "css" . DIRECTORY_SEPARATOR
            . "module_IE7.css"
        ) || file_exists(
            $_SESSION['config']['corepath'] . 'modules' . DIRECTORY_SEPARATOR
            . $_SESSION['modules_loaded'][$value]['name'] . DIRECTORY_SEPARATOR
            . "css" . DIRECTORY_SEPARATOR . "module_IE7.css"
        )
        ) {
            include 'modules/' . $_SESSION['modules_loaded'][$value]['name']
                . '/css/module_IE7.css';
        }
    }
} else if (isset($_GET['ie8']) && file_exists('apps/' . $_SESSION['config']['app_id'] . '/css/style_ie8.css')) {
    include 'apps/' . $_SESSION['config']['app_id'] . '/css/style_ie8.css';
    foreach (array_keys($_SESSION['modules_loaded']) as $value) {
        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'modules'
            . DIRECTORY_SEPARATOR . $_SESSION['modules_loaded'][$value]['name']
            . DIRECTORY_SEPARATOR . "css" . DIRECTORY_SEPARATOR
            . "module_IE8.css"
        ) || file_exists(
            $_SESSION['config']['corepath'] . 'modules' . DIRECTORY_SEPARATOR
            . $_SESSION['modules_loaded'][$value]['name'] . DIRECTORY_SEPARATOR
            . "css" . DIRECTORY_SEPARATOR . "module_IE8.css"
        )
        ) {
            include 'modules/' . $_SESSION['modules_loaded'][$value]['name']
                . '/css/module_IE8.css';
        }
    }
} else if (isset($_GET['ie9']) && file_exists('apps/' . $_SESSION['config']['app_id'] . '/css/style_ie9.css')) {
    include 'apps/' . $_SESSION['config']['app_id'] . '/css/style_ie9.css';
    foreach (array_keys($_SESSION['modules_loaded']) as $value) {
        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'modules'
            . DIRECTORY_SEPARATOR . $_SESSION['modules_loaded'][$value]['name']
            . DIRECTORY_SEPARATOR . "css" . DIRECTORY_SEPARATOR
            . "module_IE9.css"
        ) || file_exists(
            $_SESSION['config']['corepath'] . 'modules' . DIRECTORY_SEPARATOR
            . $_SESSION['modules_loaded'][$value]['name'] . DIRECTORY_SEPARATOR
            . "css" . DIRECTORY_SEPARATOR . "module_IE9.css"
        )
        ) {
            include 'modules/' . $_SESSION['modules_loaded'][$value]['name']
                . '/css/module_IE9.css';
        }
    }
} else {
    include 'apps/' . $_SESSION['config']['app_id'] . '/css/styles.css';
    foreach (array_keys($_SESSION['modules_loaded']) as $value) {
        if (file_exists(
            $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
            . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . 'modules'
            . DIRECTORY_SEPARATOR . $_SESSION['modules_loaded'][$value]['name']
            . DIRECTORY_SEPARATOR . "css" . DIRECTORY_SEPARATOR . "module.css"
        ) || file_exists(
            $_SESSION['config']['corepath'] . 'modules' . DIRECTORY_SEPARATOR
            . $_SESSION['modules_loaded'][$value]['name'] . DIRECTORY_SEPARATOR
            . "css" . DIRECTORY_SEPARATOR . "module.css"
        )
        ) {
            include 'modules/' . $_SESSION['modules_loaded'][$value]['name']
                . '/css/module.css';
        }
    }
}
include_once 'apps/' . $_SESSION['config']['app_id'] . '/css/doctype_levels.css';
include_once 'apps/' . $_SESSION['config']['app_id'] . '/css/chosen.min.css';
ob_end_flush();

