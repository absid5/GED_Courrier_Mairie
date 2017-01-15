<?php
/**
* File : res_select_folder.php
*
* Result of a form
*
* @package  Maarch PeopleBox 1.0
* @version 2.0
* @since 06/2006
* @license GPL
* @author  Claire Figueras  <dev@maarch.org>
*/


require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
$core_tools = new core_tools();
$core_tools->load_lang();
require_once("modules".DIRECTORY_SEPARATOR."folder".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_modules_tools.php");

 if(!isset($_REQUEST['field']) || empty($_REQUEST['field']))
 {
 	header("location: ".$_SESSION['config']['businessappurl']."index.php?display=true&module=folder&page=result_folder");
 	exit;
 }
 else
 {
 	$folder = new folder();
 	$folder->load_folder(trim($_REQUEST['field']), $_SESSION['tablename']['fold_folders']);
 	$_SESSION['current_folder_id'] = $folder->get_field('folders_system_id');
 	$folder->modify_default_folder_in_db($_SESSION['current_folder_id'], $_SESSION['user']['UserId'], $_SESSION['tablename']['users']);

	 ?>

 	<script type="text/javascript">
		//window.alert(window.top.location);
		if(window.top.name == 'CreateFolder')
		{

			window.top.opener.top.opener.location.reload();window.top.opener.close();window.top.close();
		}
		else // opener = index_file
		{

			<?php

			if($_SESSION['physical_archive_origin'] == 'true')
			{
				
				?>

				var eleframe1 = window.top.document.getElementById('myframe');
				eleframe1.src = '<?php echo $_SESSION['config']['businessappurl']?>index.php?display=true&module=physical_archive&page=select_types_for_pa';

				<?php
			}
			elseif($_SESSION['origin'] <> 'store_file')
			{
			?>
				var eleframe1 = window.top.frames['index'].document.getElementById('myframe');
				eleframe1.src = '<?php echo $_SESSION['config']['businessappurl']?>index.php?display=true&module=physical_archive&page=select_type';
				
			<?php
			}
			else
			{
			?>
				window.top.location.reload();
			<?php
			}
			?>
		}
 	</script>
 	<?php
 }
?>
