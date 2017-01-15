<?php
/**
* File : missing_res.php
*
* Frame to show a the missing doc of a folder
*
* @package  Maarch PeopleBox 1.0
* @version 1.0
* @since 10/2006
* @license GPL
* @author  Claire Figueras  <dev@maarch.org>
*/
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
$core_tools = new core_tools();
$core_tools->load_lang();
require_once("modules".DIRECTORY_SEPARATOR."folder".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_modules_tools.php");
$table ="";
if(isset($_SESSION['collection_choice']) && !empty($_SESSION['collection_choice']))
{
	$table = $_SESSION['collection_choice'];
}
else
{
	$table = $_SESSION['collections'][0]['table'];
}
$missing_res = array();

if(isset($_SESSION['current_folder_id']) && !empty($_SESSION['current_folder_id']))
{
	$folder = new folder();
	$folder->load_folder($_SESSION['current_folder_id'], $_SESSION['tablename']['fold_folders']);
	$foldertype_id = $folder->get_field('foldertype_id');
	$missing_res = $folder->missing_res($table, $_SESSION['tablename']['fold_foldertypes_doctypes'], $_SESSION['tablename']['doctypes'], $_SESSION['current_folder_id'], $foldertype_id);
}
$core_tools->load_html();
//here we building the header
$core_tools->load_header('', true, false);
?>
<body id="missing_iframe">
<?php
if(count($missing_res) < 1 && isset($_SESSION['current_folder_id'])&& !empty($_SESSION['current_folder_id']))
{
	echo _FOLDER.' '.strtolower(_COMPLETE);
}
else if (count($missing_res) < 1 && !isset($_SESSION['current_folder_id']) )
{
	echo _PLEASE_SELECT_FOLDER.".";
}
else
{
	?>
		<table width="95%" class="listing" border="0" cellspacing="0">
        	<thead>
            	<tr>
                	<th width="30%"><?php echo _ID;?></th>
                    <th><?php echo _DESC;?></th>
                </tr>
            </thead>
            <tbody>
			<?php
				$color = "";
				for($cpt_missing_res=0; $cpt_missing_res < count($missing_res); $cpt_missing_res++)
				{
					if($color == ' class="col"')
					{
						$color = '';
					}
					else
					{
						$color = ' class="col"';
					}
					?>
					<tr <?php echo $color;?>>
                   		 <td>
							<?php functions::xecho($missing_res[$cpt_missing_res]['ID']);?>
						</td>
						<td>
							<?php functions::xecho($missing_res[$cpt_missing_res]['LABEL']);?>
						</td>
					</tr>
					<?php
				}
			?>
            </tbody>
		</table>
	<?php
}
$core_tools->load_js();
?>
</body>
</html>
