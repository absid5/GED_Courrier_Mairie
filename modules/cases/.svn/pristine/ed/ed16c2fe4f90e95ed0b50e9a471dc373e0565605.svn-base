<?php

require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_security.php");
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_manage_status.php");
require_once('apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR."class_indexing_searching_app.php");
require_once('apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR."class_types.php");
require_once("modules".DIRECTORY_SEPARATOR."cases".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR.'class_modules_tools.php');

$core_tools = new core_tools();
$core_tools->test_user();
$core_tools->load_lang();

if (($core_tools->test_service('join_res_case', 'cases', false) == 1) || ($core_tools->test_service('join_res_case_in_process', 'cases', false) == 1))
{
    $cases = new cases();
    
    if ($_GET['searched_item'] == "case")
    {
            $res_id_to_insert = $_POST['field'];
            $actual_case_id = $_GET['searched_value'];

            if (!empty($res_id_to_insert ) && !empty($actual_case_id))
            {
                if($cases->join_res($actual_case_id, $res_id_to_insert)==true)
                {
                    $_SESSION['info'] = _RESSOURCES_LINKED;
                }
                else
                {
                    $_SESSION['info'] = _RESSOURCES_NOT_LINKED;
                }
                ?>
                <script type="text/javascript">
                window.opener.top.location.href='<?php echo $_SESSION['config']['businessappurl'];?>index.php?page=details_cases&module=cases&id=<?php functions::xecho($_GET['searched_value']);?>';
                //window.opener.top.location.reload();
                //self.close();
                window.top.close();
                </script>
                <?php
            }
            else
            {
                echo _ERROR_WITH_CASES_ATTACHEMENT;
            }
    }
    elseif ($_GET['searched_item'] == "res_id")
    {
            $case_id_to_insert = $_POST['field'];
            $actual_res_id = $_GET['searched_value'];

            if (!empty($case_id_to_insert ) && !empty($actual_res_id))
            {
                if($cases->join_res($case_id_to_insert, $actual_res_id)==true)
                {
                    $error = _RESSOURCES_LINKED;
                }
                else
                {
                    $error = _RESSOURCES_NOT_LINKED;
                }
                ?>
                <script type="text/javascript">
                    window.opener.top.location.reload();
                    self.close();
                    /*var error_div = window.opener.$('main_error');
                    if(error_div)
                    {
                        error_div.update('<?php functions::xecho($error );?>');
                    }*/
                </script>
                <?php
            }
            else
            {
                echo _ERROR_WITH_CASES_ATTACHEMENT;
            }
    }
    elseif ($_GET['searched_item'] == "res_id_in_process")
    {
            $case_id_to_insert = $_POST['field'];
            $actual_res_id = $_GET['searched_value'];

            if (!empty($case_id_to_insert ) && !empty($actual_res_id))
            {
                if($cases->join_res($case_id_to_insert, $actual_res_id)==true)
                {
                    $error = _RESSOURCES_LINKED;
                }
                else
                {
                    $error = _RESSOURCES_NOT_LINKED;
                }
                
                
                // Update Main process frame
                
                $cases_return = new cases();
                $return_description = array();
                $return_description = $cases_return->get_case_info($case_id_to_insert);
                ?>
                <script type="text/javascript">	
                var case_id = window.opener.$('case_id');
                var case_label = window.opener.$('case_label');
                var case_description = window.opener.$('case_description');
                
                if(case_id)
                {
                    case_id.value = '<?php functions::xecho($return_description['case_id'] ); ?>';
                    case_label.value = '<?php echo addslashes($return_description['case_label']); ?>';
                    case_description.value = '<?php echo addslashes($return_description['case_description']); ?>';
                    
                }
                //self.close();
                window.top.close();
            
                </script>
                <?php
            }
            else
            {
                echo _ERROR_WITH_CASES_ATTACHEMENT;
            }
    }
}
