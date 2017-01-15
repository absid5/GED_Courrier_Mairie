<?php
require_once 'modules/templates/class/templates_controler.php';
require_once 'modules/templates/templates_tables_definition.php';
require_once 'modules/attachments/attachments_tables.php';
$templates_controler = new templates_controler();
$db = new Database();
$core = new core_tools();
$core->load_lang();
$_SESSION['template_content'] = '';
if (isset($_REQUEST['template_content']) && !empty($_REQUEST['template_content'])) {
    $_SESSION['template_content'] = stripslashes($_REQUEST['template_content']);
    $_SESSION['upfile']['format'] = 'maarch';
    $_SESSION['upfile']['name'] = 'tmp_file_' 
                . $_SESSION['user']['UserId'] . '_' . rand() . '.maarch';
    $tmpPath = $_SESSION['config']['tmppath'] . DIRECTORY_SEPARATOR
             . $_SESSION['upfile']['name'];
    $myfile = fopen($tmpPath, "w");
    fwrite($myfile, $_SESSION['template_content']);
    fclose($myfile);
    $_SESSION['upfile']['size'] = filesize($tmpPath);
} else {
    if (isset($_REQUEST['model_id']) && !empty($_REQUEST['model_id'])) {
        $template = $templates_controler->get($_REQUEST['model_id']);
        $_SESSION['template_content'] = stripslashes($template->template_content);
        $_SESSION['upfile']['format'] = 'maarch';
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $_SESSION['config']['lang'] ?>" lang="<?php echo $_SESSION['config']['lang'] ?>">
<head>
    <title><?php functions::xecho($_SESSION['config']['applicationname']);?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="Content-Language" content="<?php echo $_SESSION['config']['lang'] ?>" />
    <link rel="stylesheet" type="text/css" href="<?php functions::xecho($_SESSION['config']['css']);?>" media="screen" />
    <!--[if lt IE 7.0]>  <link rel="stylesheet" type="text/css" href="<?php functions::xecho($_SESSION['config']['css_IE']);?>" media="screen" />  <![endif]-->
    <!--[if gte IE 7.0]>  <link rel="stylesheet" type="text/css" href="<?php functions::xecho($_SESSION['config']['css_IE7']);?>" media="screen" />  <![endif]-->
    <script type="text/javascript" src="js/functions.js"></script>
    <?php
    $_SESSION['mode_editor'] = false;
    include('modules/templates/load_editor.php');
    ?>
</head>
<body>
    <form name="frmtemplate" id="frmtemplate" method="post">
        <textarea name="template_content" id="template_content" style="width:98%" rows="40">
            <?php functions::xecho($_SESSION['template_content']);?>
        </textarea>
        <p>
            <!--<input type="submit" class="button" name="valid" id="valid" value="<?php echo _VALID_TEXT;?>" />-->
        </p>
    </form>
</body>
</html>
