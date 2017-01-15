<?php
/**
* File : details_cases.php
*
* Detailed informations on an selected cases
*
* @package  Maarch Entreprise 1.0
* @version 1.0
* @since 10/2005
* @license GPL
* @author  Loïc Vinet  <dev@maarch.org>
*/
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_security.php");
require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_list_show.php");
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_history.php");
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_manage_status.php");
require_once("modules".DIRECTORY_SEPARATOR."cases".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR.'class_modules_tools.php');
$core_tools = new core_tools();
$core_tools->test_user();
$core_tools->load_lang();
$core_tools->load_html();
$core_tools->load_header('', true, false);
$sec = new security();
$cases = new cases();

$status_obj = new manage_status();
if (($core_tools->test_service('join_res_case', 'cases', false) == 1) || ($core_tools->test_service('join_res_case_in_process', 'cases', false) == 1))
{
    $case_label = $_POST['case_label'];
    $case_description = $_POST['case_description'];
    $case_description = str_replace("\n"," ",$case_description);
    $case_description = str_replace("\r","",$case_description);
    $actual_res_id = $_POST['searched_value'];
    if($case_label <> '' && $actual_res_id <> '')
    {
        if (!$cases->create_case($actual_res_id, $case_label, $case_description))
        {
            echo 'CASES ATTACHEMENT ERROR'; 
        }
        else
        {
            if($_POST['searched_item'] == 'res_id_in_process')
            {
                $case_redemption = new cases();
                $case_id_newest = $case_redemption->get_case_id($actual_res_id);
                ?>
                <script type="text/javascript">
                var case_id = window.opener.$('case_id');
                var case_label = window.opener.$('case_label');
                var case_description = window.opener.$('case_description');
                if(case_id)
                {
                    case_id.value = '<?php functions::xecho($case_id_newest );?>';
                    case_label.value = '<?php echo addslashes($case_label);?>';
                    case_description.value = '<?php echo addslashes($case_description);?>';
                }
                self.close();
                </script>
                <?php
            }
            else
            {   
                $error = _CASE_CREATED;
                ?>
                <script type="text/javascript">
                window.opener.top.location.reload();
                /*var error_div = window.opener.$('main_error');
                if(error_div)
                {
                    error_div.update('<?php functions::xecho($error );?>');
                }*/
                self.close();
                </script>
                <?php
            }
        }
    }
    else
    {
        $_SESSION['cases_error'] = _LABEL_MANDATORY;
        ?>
        <script type="text/javascript">
            window.history.back();
        </script>
        <?php
    }
}
$core_tools->load_js();
?>
