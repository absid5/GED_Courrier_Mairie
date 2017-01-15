<?php

$processIDS = true;
for ($cptIDS=0;$cptIDS<count($_SESSION['PHPIDS_EXCLUDES']);$cptIDS++) {
    if (
            (
                $_REQUEST['module'] == $_SESSION['PHPIDS_EXCLUDES'][$cptIDS]['TARGET'] ||
                $_REQUEST['admin']  == $_SESSION['PHPIDS_EXCLUDES'][$cptIDS]['TARGET'] || 
                $_SESSION['PHPIDS_EXCLUDES'][$cptIDS]['TARGET'] == ""
            )
        &&  
            $_REQUEST['page'] == $_SESSION['PHPIDS_EXCLUDES'][$cptIDS]['PAGE']
    ) {
        $processIDS = false;
        break;
    }
}

if ($processIDS) {
    set_include_path(
        get_include_path()
        . PATH_SEPARATOR
        . 'apps/maarch_entreprise/tools/phpids/lib/'
    );

    require_once 'IDS/Init.php';

    try {

        $request = array(
            'REQUEST' => $_REQUEST,
            'GET' => $_GET,
            'POST' => $_POST,
            'COOKIE' => $_COOKIE
        );

        $init = IDS_Init::init(
            dirname(__FILE__) 
            . '/tools/phpids/lib/IDS/Config/Config.ini.php'
        );
        
        $init->config['General']['base_path'] = dirname(__FILE__) 
            . '/tools/phpids/lib/IDS/';
        $init->config['General']['use_base_path'] = true;
        $init->config['Caching']['caching'] = 'none';

        // 2. Initiate the PHPIDS and fetch the results
        $ids = new IDS_Monitor($request, $init);
        $result = $ids->run();

        if (!$result->isEmpty()) {
            require_once 'core/class/class_core_tools.php';
            require_once 'core/class/class_history.php';
            $hist = new history();
            $ip = $_SERVER['REMOTE_ADDR'];
            $hist->add(
                $_SESSION['tablename']['users'],
                $_SESSION['user']['UserId'],
                'PHPIDS','phpidscontrol',
                ' PHPIDS CONTROL, USER : ' . $_SESSION['user']['UserId'] . ' IP : ' . $ip  
                    . ' MESSAGE : ' 
                    . (string) $result,
                $_SESSION['config']['databasetype'],
                'admin'
                 ,
                false,
                _OK,
                _LEVEL_ERROR
            );
            if ($_SESSION['config']['debug'] == 'true') {
                echo $result;
                $_SESSION['securityMessage'] = (string) $result;
                $varRedirect = '<script language="javascript">window.location.href=\'' 
                    . $_SESSION['config']['businessappurl'] 
                    . "index.php?page=security_message';</script>";
                echo $varRedirect;
                exit;
            } elseif ($result->getImpact() >= 30) {
                $_SESSION['securityMessage'] = (string) $result;
                $varRedirect = '<script language="javascript">window.location.href=\'' 
                    . $_SESSION['config']['businessappurl'] 
                    . "index.php?page=security_message';</script>";
                echo $varRedirect;
                exit;
            }
        }
    } catch (Exception $e) {
        /*
        * sth went terribly wrong - maybe the
        * filter rules weren't found?
        */
        printf(
            'An error occured: %s',
            $e->getMessage()
        );
    }
}
