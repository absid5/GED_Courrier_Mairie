<?php
/**
*
*
* @package  Maarch Entreprise 1.0
* @version 1.0
* @since 10/2005
* @license GPL
* @author  Loïc Vinet  <dev@maarch.org>
*/
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_security.php");
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_manage_status.php");
require_once('apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR."class_indexing_searching_app.php");
require_once('apps'.DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR.'class'.DIRECTORY_SEPARATOR."class_types.php");
require_once("modules".DIRECTORY_SEPARATOR."cases".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR.'class_modules_tools.php');

$core_tools = new core_tools();
$core_tools->test_user();
$core_tools->load_lang();
$core_tools->test_service('join_res_case', 'cases');
$cases = new cases();
$security = new security();

if ($_GET['case_id'])
{

		$case_id = $_GET['case_id']; 
		
		$array_res = array();
		$array_res = $cases->get_res_id($case_id);
		
		$rights = false;
		foreach ($array_res as $res)
		{
			$right = $security->test_right_doc($coll_id, $s_id);
			if ($right == false)
			{
				$rights = true;
			}
		}
		
		if ($rights ==true)
		{
			//Lanching closing date
			$cases->close_case($case_id);
			$_SESSION['info'] =  _THIS_CASE_IS_CLOSED;
			?>
			<script type="text/javascript">
			window.parent.top.location.reload();self.close();
			</script>
			<?php
		}
		else
		{
			echo _ERROR_WITH_CASES_ATTACHEMENT;
		}
}
else
{
	echo _ERROR_TO_CLOSING_DATE;
	
}
