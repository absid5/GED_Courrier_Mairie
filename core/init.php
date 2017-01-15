<?php

require_once dirname(__file__) . '/class/Url.php';
//dynamic session name
$sessionName = str_replace("\\","/", dirname(__file__));
$sessionName = str_replace($_SERVER['DOCUMENT_ROOT'], '', $sessionName);
$sessionName = str_replace("/", '', $sessionName);
$sessionName = str_replace('core', '', $sessionName);
if ($sessionName == '') {
    $sessionName = 'maarch';
}
$secure = $_SERVER["HTTPS"];
$httponly = true;
$cookieParams = session_get_cookie_params();
session_set_cookie_params(
    0, 
    $cookieParams["path"], 
    $cookieParams["domain"], 
    $secure, 
    $httponly
);
session_name($sessionName);
session_start();

$_SESSION['sessionName'] = $sessionName;

if (!isset($_SESSION['config']) || !isset($_SESSION['businessapps'][0]['appid'])) {
    require_once('class/class_portal.php');
    $portal = new portal();
    $portal->unset_session();
    $portal->build_config();
}
if (isset($_SESSION['config']['default_timezone'])
    && ! empty($_SESSION['config']['default_timezone'])
) {
    ini_set('date.timezone', $_SESSION['config']['default_timezone']);
    date_default_timezone_set($_SESSION['config']['default_timezone']);
} else {
    ini_set('date.timezone', 'Europe/Paris');
    date_default_timezone_set('Europe/Paris');
}

if (isset($_SESSION['config']['corepath'])
    && ! empty($_SESSION['config']['corepath'])
) {
    chdir($_SESSION['config']['corepath']);
}
//ini_set('error_reporting', E_ALL);
if (isset($_SESSION['custom_override_id'])
    && ! empty($_SESSION['custom_override_id'])
    && isset($_SESSION['config']['corepath'])
    && ! empty($_SESSION['config']['corepath'])
) {
    $path = $_SESSION['config']['corepath'] . 'custom' . DIRECTORY_SEPARATOR
        . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR;
    set_include_path(
        $path . PATH_SEPARATOR . $_SESSION['config']['corepath']
        . PATH_SEPARATOR . get_include_path()
    );
} else if (isset($_SESSION['config']['corepath'])
    && ! empty($_SESSION['config']['corepath'])
) {
    set_include_path(
        $_SESSION['config']['corepath'] . PATH_SEPARATOR . get_include_path()
    );
}


/**
 * Get an array that represents directory tree
 * @param string $directory     Directory path
 * @param bool $recursive         Include sub directories
 * @param bool $listDirs         Include directories on listing
 * @param bool $listFiles         Include files on listing
 * @param regex $exclude         Exclude paths that matches this regex
 */
function maarchFilesWhiteList($directory, $isCustom = false, $recursive = true, $listDirs = false, $listFiles = true, $exclude = '')
{
    $arrayItems = array();
    $skipByExclude = false;
    $handle = opendir($directory);
    if ($handle) {
        while (false !== ($file = readdir($handle))) {
            preg_match("/(^(([\.]){1,2})$|"
                . "(\.(svn|git|md|xml|default|inc|js|sql|html|sh|bat|txt|log|css|jpg|jpeg|png|gif|doc|docx"
                . "|xls|xlsx|odt|ods|csv|pdf|rb|jar|svg|psd|msi|vbs))|(Thumbs\.db|\.DS_STORE|tools|css|js|img|lang|"
                . "sql|tmp|log|logs|xml))$/iu", $file, $skip);
            if ($exclude) {
                preg_match($exclude, $file, $skipByExclude);
            }
            if (!$skip && !$skipByExclude) {
                if (is_dir($directory. DIRECTORY_SEPARATOR . $file)) {
                    if ($recursive) {
                        $arrayItems = array_merge(
                            $arrayItems, 
                            maarchFilesWhiteList(
                                $directory. DIRECTORY_SEPARATOR . $file, 
                                $isCustom, 
                                $recursive, 
                                $listDirs, 
                                $listFiles, 
                                $exclude
                            )
                        );
                    }
                    if ($listDirs) {
                        $fileName = $file;
                        $file = $directory . DIRECTORY_SEPARATOR . $file;
                        $arrayItems[$fileName] = $file;
                    }
                } else {
                    if ($listFiles) {
                        //$fileName = $file;
                        $file = $directory . DIRECTORY_SEPARATOR . $file;
                        if (
                            $isCustom 
                            && file_exists('custom/' . $_SESSION['custom_override_id'] . '/' . $file)
                        ) {
                            //$arrayItems[$fileName] = 'custom/' . $_SESSION['custom_override_id'] . '/' . $file;
                            array_push($arrayItems, 'custom'. DIRECTORY_SEPARATOR . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR . $file);
                        } else {
                            //$arrayItems[$fileName] = $file;
                            array_push($arrayItems, $file);
                        }
                    }
                }
            }
        }
        closedir($handle);
    }
    return $arrayItems;
}


if ((!isset($_SESSION['maarchFilesWhiteList']) && count($_SESSION['maarchFilesWhiteList']) == 0) || $_SESSION['maarchFilesWhiteListTurn'] == 1 ) {
    $isCustom = false;
    if (
        is_dir('custom/' . $_SESSION['custom_override_id']) 
        && !empty($_SESSION['custom_override_id'])
    ) {
        $isCustom = true;
    }

    if(!isset($_SESSION['maarchFilesWhiteListTurn'])){
        $_SESSION['maarchFilesWhiteListTurn'] = 1;
    } else {
        $_SESSION['maarchFilesWhiteListTurn'] = 2;
    }

    $_SESSION['maarchFilesWhiteList'] = array();
    $_SESSION['maarchFilesWhiteList']['core'] = maarchFilesWhiteList('core', $isCustom);
    $_SESSION['maarchFilesWhiteList']['apps'] = maarchFilesWhiteList('apps', $isCustom);
    $_SESSION['maarchFilesWhiteList']['modules'] = array();

    $modules = array();
    $handle = opendir('modules');
    if ($handle) {
        while (false !== ($file = readdir($handle))) {
            //echo $file . '<br/>';
            if (is_dir($_SESSION['config']['corepath'] . '/modules/' . $file) && $file <> '.' && $file <> '..') {
                array_push($modules, $file);
            }
        }
    }
    $countModules = count($modules);
    for ($z=0;$z<$countModules;$z++) {
        $_SESSION['maarchFilesWhiteList']['modules'][$modules[$z]] = maarchFilesWhiteList(
            'modules' . DIRECTORY_SEPARATOR . $modules[$z],
            $isCustom
        );
    }
    if (
        is_dir($_SESSION['config']['corepath'] . '/custom/' . $_SESSION['custom_override_id']) 
        && !empty($_SESSION['custom_override_id'])
    ) {
        if (is_dir($_SESSION['config']['corepath'] . 'custom/' . $_SESSION['custom_override_id'] . '/core')) {
            $_SESSION['maarchFilesWhiteList']['custom']['core'] = maarchFilesWhiteList('custom'. DIRECTORY_SEPARATOR . $_SESSION['custom_override_id'] . DIRECTORY_SEPARATOR .'core');
            $_SESSION['maarchFilesWhiteList']['core'] 
                = array_merge($_SESSION['maarchFilesWhiteList']['core'], $_SESSION['maarchFilesWhiteList']['custom']['core']);
        }
        if (is_dir($_SESSION['config']['corepath'] . 'custom/' . $_SESSION['custom_override_id']. '/apps')) {
            $_SESSION['maarchFilesWhiteList']['custom']['apps'] = maarchFilesWhiteList('custom' . DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']. DIRECTORY_SEPARATOR .'apps');
            $_SESSION['maarchFilesWhiteList']['apps'] 
                = array_merge($_SESSION['maarchFilesWhiteList']['apps'], $_SESSION['maarchFilesWhiteList']['custom']['apps']);

        }
        for ($z=0;$z<$countModules;$z++) {
            if (is_dir($_SESSION['config']['corepath'] . 'custom/' . $_SESSION['custom_override_id']. '/modules/' . $modules[$z])) {
                $_SESSION['maarchFilesWhiteList']['custom']['modules'][$modules[$z]] = maarchFilesWhiteList(
                    'custom'. DIRECTORY_SEPARATOR . $_SESSION['custom_override_id']. DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $modules[$z],
                    $isCustom
                );
                $_SESSION['maarchFilesWhiteList']['modules'][$modules[$z]] 
                    = array_merge($_SESSION['maarchFilesWhiteList']['modules'][$modules[$z]], $_SESSION['maarchFilesWhiteList']['custom']['modules'][$modules[$z]]);
            }
            
        }
    }

    // echo '<pre>';
    // print_r($_SESSION['maarchFilesWhiteList']);
    // echo '</pre>';
    // exit;
}


