<?php
/*
*   Copyright 2008-2015 Maarch
*
*   This file is part of Maarch Framework.
*
*   Maarch Framework is free software: you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation, either version 3 of the License, or
*   (at your option) any later version.
*
*   Maarch Framework is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*   GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

/****************************************************************************/
/*                                                                          */
/*                                                                          */
/*               THIS PAGE CAN NOT BE OVERWRITTEN IN A CUSTOM               */
/*                                                                          */
/*                                                                          */
/* **************************************************************************/

/**
* @brief Maarch index page : every php page is loaded with this page
*
* @file
* @author  Claire Figueras  <dev@maarch.org>
* @author  Laurent Giovannoni <dev@maarch.org>
* @author  Loic Vinet  <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup apps
*/
include_once '../../core/class/class_functions.php';
include_once '../../core/class/class_db_pdo.php';
include_once '../../core/init.php';

if ($_SESSION['config']['usePHPIDS'] == 'true') {
    include 'apps/maarch_entreprise/phpids_control.php';
}

if (isset($_SESSION['config']['corepath'])) {
    require_once 'core/class/class_functions.php';
    require_once 'core/class/class_db.php';
    require_once 'core/class/class_core_tools.php';
    $core = new core_tools();
    if (! isset($_SESSION['custom_override_id'])
        || empty($_SESSION['custom_override_id'])
    ) {
        $_SESSION['custom_override_id'] = $core->get_custom_id();
        if (! empty($_SESSION['custom_override_id'])) {
            $path = $_SESSION['config']['corepath'] . 'custom/'
                  . $_SESSION['custom_override_id'] . '/';
            set_include_path(
                $path . '/' . $_SESSION['config']['corepath']
            );
        }
    }
} else {
    require_once '../../core/class/class_functions.php';
    require_once '../../core/class/class_db.php';
    require_once '../../core/class/class_core_tools.php';
    $core = new core_tools();
    $_SESSION['custom_override_id'] = $core->get_custom_id();
    chdir('../..');
    if (! empty($_SESSION['custom_override_id'])) {
        $path = $_SESSION['config']['corepath'] . 'custom/'
              . $_SESSION['custom_override_id'] . '/';
        set_include_path(
            $path . '/' . $_SESSION['config']['corepath']
        );
    }
}

$core->load_lang();

if (isset($_REQUEST['dir']) && !empty($_REQUEST['dir'])) {    
    $_REQUEST['dir'] = str_replace("\\", "", $_REQUEST['dir']);
    $_REQUEST['dir'] = str_replace("/", "", $_REQUEST['dir']);
    $_REQUEST['dir'] = str_replace("..", "", $_REQUEST['dir']);
}

include 'apps/maarch_entreprise/tools/maarchIVS/MaarchIVS.php';
$started = MaarchIVS::start(__DIR__ . '/xml/IVS/requests_definitions.xml', 'xml');
$valid = MaarchIVS::run('silent');
if (!$valid) {
    $validOutpout = MaarchIVS::debug();
    $cptValid = count($validOutpout['validationErrors']);
    $error = '';
    for ($cptV=0;$cptV<=count($cptValid);$cptV++) {
        $message = $validOutpout['validationErrors'][$cptV]->message;
        if ($message == "Length id below the minimal length") {
            $message = _IVS_LENGTH_ID_BELOW_MIN_LENGTH;
        } elseif ($message == "Length exceeds the maximal length") {
            $message = _IVS_LENGTH_EXCEEDS_MAX_LENGTH;
        } elseif ($message == "Length is not allowed") {
            $message = _IVS_LENGTH_NOT_ALLOWED;
        } elseif ($message == "Value is not allowed") {
            $message = _IVS_VALUE_NOT_ALLOWED;
        } elseif ($message == "Format is not allowed") {
            $message = _IVS_FORMAT_NOT_ALLOWED;
        } elseif ($message == "Value is below the minimal value") {
            $message = _IVS_VALUE_BELOW_MIN_VALUE;
        } elseif ($message == "Value exceeds the maximal value") {
            $message = _IVS_LENGTH_EXCEEDS_MAX_LENGTH;
        } elseif ($message == "Too many digits") {
            $message = _IVS_TOO_MANY_DIGITS;
        } elseif ($message == "Too many decimal digits") {
            $message = _IVS_TOO_MANY_DECIMAL_DIGITS;
        }
        $error .= $message . PHP_EOL;
        $error .= $validOutpout['validationErrors'][$cptV]->parameter . PHP_EOL;
        $error .= $validOutpout['validationErrors'][$cptV]->value . PHP_EOL;
    }
    foreach ($_REQUEST as $name => $value) {
        if (is_string($value) && strpos($value, "<") !== false) {
            $value = preg_replace('/(<\/?script[^>]*>|<\?php|<\?[\s|\n|\r])/i', "", $value);
            $_REQUEST[$name] = $value;
            if (isset($_GET[$name]) && $_GET[$name] <> '') {
                $_GET[$name] = $value;
            }
            if (isset($_POST[$name]) && $_POST[$name] <> '') {
                $_POST[$name] = $value;
            }
        }
        $value = str_replace("\\", "", $value);
        $value = str_replace("/", "", $value);
        $value = str_replace("..", "", $value);
        $_REQUEST[$name] = $value;
        if (isset($_GET[$name]) && $_GET[$name] <> '') {
            $_GET[$name] = $value;
        }
        if (isset($_POST[$name]) && $_POST[$name] <> '') {
            $_POST[$name] = $value;
        }
    }
    //process error for ajax request 
    if (
        array_key_exists('HTTP_X_REQUESTED_WITH', $_SERVER) 
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
    ) {
        echo $error;
        exit;
    } else {
        //process error for standard request
        $_SESSION['error'] = $error;
    }
} else {
    //Request is valid
}

if (
    isset($_SESSION['user']['UserId']) 
    && isset($_GET['page'])
    && !empty($_SESSION['user']['UserId']) && $_GET['page'] <> 'login'
    && $_GET['page'] <> 'log' && $_GET['page'] <> 'logout'
) {
    $db = new Database();
    $key = md5(
        time() . '%' . $_SESSION['user']['FirstName'] . '%'
        . $_SESSION['user']['UserId'] . '%' . $_SESSION['user']['UserId']
        . '%' . date('dmYHmi') . '%'
    );

    // $db->query(
    //     'UPDATE ' . $_SESSION['tablename']['users'] 
    //         . " SET cookie_key = ?, cookie_date = CURRENT_TIMESTAMP WHERE user_id = ? and mail = ?", 
    //     array($key, $_SESSION['user']['UserId'], $_SESSION['user']['Mail']),1
    // );
}

if (
    !isset($_SESSION['user']['UserId']) 
    && $_REQUEST['page'] <> 'login' 
    && $_REQUEST['page'] <> 'log'
) {
    $_SESSION['HTTP_REFERER'] = Url::requestUri();
    if (trim($_SERVER['argv'][0]) <> '') {
        header('location: reopen.php?' . $_SERVER['argv'][0]);
    } else {
        header('location: reopen.php');
    }
    exit();
}

if (isset($_REQUEST['display'])) {
     $core->insert_page();
     exit();
}

if (isset($_GET['show'])) {
    $show = $_GET['show'];
} else {
    $show = 'true';
}

$core->start_page_stat();
$core->configPosition();
if (isset($_SESSION['HTTP_REFERER'])) {
    $url = $_SESSION['HTTP_REFERER'];
    unset($_SESSION['HTTP_REFERER']);
    header('location: '.$url);
}

$core->load_html();
$core->load_header();
$time = $core->get_session_time_expire();

 ?>
<body style="background: #f2f2f2;" onload="session_expirate(<?php echo $time;?>, '<?php 
    echo $_SESSION['config']['businessappurl'];
    ?>index.php?display=true&page=logout&logout=true');" id="maarch_body">


    <?php
    //do it only once
    if (empty($_SESSION['clientSideCookies'])) {
        ?>
        <script type="text/javascript">
            function getCookies() {
                
                //document.cookie = "maarch_cookie_1=thefirstcookie";
                //document.cookie = "maarch_cookie_2=thesecondcookie";
                //console.log(document.cookie);
                return document.cookie;
            };

            var theCookies;
            //theCookies = getCookies().split(";");
            theCookies = getCookies();
            
            
            if (theCookies != undefined) {
                //console.log('The cookies...');
                //console.log(theCookies);
                var path_manage_script = '<?php echo $_SESSION["config"]["businessappurl"];?>' + 'index.php?display=true&page=setProxyCookies';

                new Ajax.Request(path_manage_script,
                {
                    method:'post',
                    parameters: {
                        cookies : theCookies
                    },
                    onSuccess: function(answer)
                    {
                        eval('response = ' + answer.responseText);
                        //console.log(response);
                        if (response.status == '0') {
                            //console.log('OK !!! COOKIES FROM PROXY SET');
                        } else {
                            //console.log('KO... COOKIES FROM PROXY NOT SET');
                        }
                        
                    }
                });
            } else {
                //console.log('no completements cookies');
            }
        </script>  
        <?php
    }

if (!isset($_REQUEST['display'])) { ?>
    <script>
        var element = document;
        element.addEventListener('click', function() {
            window.clearTimeout(window.chronoExpiration);
            window.chronoExpiration=window.setTimeout('redirect_to_url(\'<?php echo $_SESSION['config']['businessappurl']; ?>index.php?display=true&page=logout&logout=true\')', '<?php echo $_SESSION['config']['cookietime']; ?>'*60*1000);
        });
    </script>
<?php }

$path = $_SESSION['config']['corepath'] . 'custom/'
      . $_SESSION['custom_override_id'] . '/apps/maarch_entreprise/template/header.html';

if (file_exists($path)) {
    include_once('custom/' . $_SESSION['custom_override_id'] 
        . '/apps/maarch_entreprise/template/header.html');
} else {
    include_once('apps/maarch_entreprise/template/header.html');
}
?>

    <div id="container">
        <div id="content">
            <div class="error" id="main_error" onclick="this.hide();"></div>
            <?php
            if(isset($_SESSION['error'])) {
                ?>
                <div class="error" id="main_error_popup" onclick="this.hide();">
                    <?php
                    echo functions::xssafe($_SESSION['error']);
                    ?>
                </div>
                <?php
            }

            if(isset($_SESSION['info'])) {
                ?>
                <div class="info" id="main_info" onclick="this.hide();">
                    <?php
                    echo functions::xssafe($_SESSION['info']);
                    ?>
                </div>
                <?php
            }
            ?>

            <?php
            if(isset($_SESSION['error']) && $_SESSION['error'] <> '') {
                ?>
                <script>
                    var main_error = $('main_error_popup');
                    if (main_error != null) {
                        main_error.style.display = 'table-cell';
                        Element.hide.delay(10, 'main_error_popup');
                    }
                </script>
                <?php
            }

            if(isset($_SESSION['info']) && $_SESSION['info'] <> '') {
                ?>
                <script>
                    var main_info = $('main_info');
                    if (main_info != null) {
                        main_info.style.display = 'table-cell';
                        Element.hide.delay(10, 'main_info');
                    }
                </script>
                <?php
            }

            echo '<div id="return_previsualise_thes" style="display: none; border-radius: 10px; box-shadow: 10px 10px 15px rgba(0, 0, 0, 0.4); padding: 10px; width: auto; height: auto; position: fixed; top: 0; left: 0; z-index: 99999; color: #4f4b47; text-shadow: -1px -1px 0px rgba(255,255,255,0.2);background:#FFF18F;border-radius:5px;overflow:auto;">\';<input type="hidden" id="identifierDetailFrame" value="" /></div>';


            if ($core->is_module_loaded('basket')
                && isset($_SESSION['abs_user_status'])
                && $_SESSION['abs_user_status'] == true) {
                include
                    'modules/basket/advert_missing.php';
            } else {
              $core->insert_page();
            }
            ?>
        </div>
        <p id="footer">
            <?php
            if (isset($_SESSION['config']['showfooter'])
                && $_SESSION['config']['showfooter'] == 'true'
            ) {
                $core->load_footer();
            }
            ?>
        </p>
        <?php
        $_SESSION['error'] = '';
        $_SESSION['info'] = '';
        $core->view_debug();
        ?>
    </div>
</body>
</html>