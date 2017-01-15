<?php
/*
*
*    Copyright 2013 Maarch
*
*  This file is part of Maarch Framework.
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
*    along with Maarch Framework.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
* @brief    Fileplan tree and list in administrator mode
*
* @file     fileplan_admin_positions.php
* @author   Yves Christian Kpakpo <dev@maarch.org>
* @date     $date$
* @version  $Revision$
* @ingroup  fileplan
*/

require_once "core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php";
require_once "modules" . DIRECTORY_SEPARATOR . "fileplan" . DIRECTORY_SEPARATOR
    . "class" . DIRECTORY_SEPARATOR . "class_modules_tools.php";
    
$core_tools = new core_tools();
$db    = new Database();
$fileplan   = new fileplan();

$core_tools->test_service('admin_fileplan', 'fileplan');

if (isset($_REQUEST['fileplan_id']) && !empty($_REQUEST['fileplan_id'])) {
	//Get fileplan ID
	$fileplan_id = $_REQUEST['fileplan_id'];
	//Get fileplan info
	$fileplanArray = $fileplan->getFileplan($fileplan_id, false);
	$fileplan_id	= $fileplanArray['ID'];
	$fileplan_label	= $fileplanArray['LABEL'];
}
	
if (!empty($fileplan_id) && $fileplan->isPersonnalFileplan($fileplan_id) === false) {
	
	/****************Management of the location bar  ************/
	$init = false;
	if(isset($_REQUEST['reinit']) && $_REQUEST['reinit'] == "true")
	{
		$init = true;
	}
	$level = "";
	if(isset($_REQUEST['level']) && ($_REQUEST['level'] == 2 || $_REQUEST['level'] == 3 || $_REQUEST['level'] == 4 || $_REQUEST['level'] == 1))
	{
		$level = $_REQUEST['level'];
	}
	$page_path = $_SESSION['config']['businessappurl'].'index.php?page=fileplan_admin_positions&module=fileplan';
	$page_label = _MANAGE_FILEPLAN_SHORT;
	$page_id = "fileplan_admin_positions";
	$core_tools->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
	/***********************************************************/

	?>
	<h1><i class="fa fa-files-o fa-2x" alt="" /></i>
		<?php echo _MANAGE_FILEPLAN;?></h1>

	<div id="inner_content">
		<div class="block">
		<h2>
			
				<span class="selected_link"><?php echo _VIEW_FILEPLAN;?></span>
				&nbsp;/&nbsp;
				<a href="<?php echo $_SESSION['config']['businessappurl'];
				?>index.php?page=fileplan_admin_managment&module=fileplan&fileplan_id=<?php
				functions::xecho($fileplan_id);?>&load" class="back">
				<?php echo _MANAGE_FILEPLAN;?></a>                
			</h2>
			&nbsp;
		<table width="100%" border="0" cellspacing="0">
			<tr>
				<td valign="top" nowrap>
					<script type="text/javascript" src="<?php 
						echo $_SESSION['config']['businessappurl'];?>tools/tafelTree/js/scriptaculous.js"></script>
					<script type="text/javascript" src="<?php 
						echo $_SESSION['config']['businessappurl'];?>tools/tafelTree/Tree.js"></script>
					<?php
					if (!empty($fileplan_id)) {
						//Get Positions for the actual fileplan
						$level_1 = array();

						$stmt = $db->query(
							"select position_id, position_label from "
							. FILEPLAN_VIEW." where fileplan_id = ?"
							. " and parent_id is null"
							. " and position_enabled = ? order by position_label asc"
						,array($fileplan_id,'Y'));
                
						while($res = $stmt->fetchObject())
						{
							array_push(
								$level_1, 
								array(
									'id' => $res->position_id, 
									'tree' => '0', 
									'key_value' => $res->position_id, 
									'label_value' => functions::show_string($fileplan->truncate($res->position_label), true), 
									'tooltip_value' => functions::show_string($res->position_label, true), 
									'color' => functions::show_string($res->label_color, true), 
									'script' => ""
								)
							);
						}
					}
					?>
					<script type="text/javascript">
						var tree = null;
						
						function funcOpen (branch, response) {
							// On peux traiter le retour et retourner true si
							// on veux insérer les enfants, false si on veux pas
							return true;
						}
						
						function view_document_list(branch) {
							var id = branch.getId();
							loadList('<?php echo $_SESSION['config']['businessappurl'];
									?>index.php?display=true&module=fileplan&page=positions_documents_list&fileplan_id=<?php 
									functions::xecho($fileplan_id);?>&id='+id, 'list_doc', true);
						}
						
						function TafelTreeInit () {
							var struct = [
											{
											'id':'0',
											'txt':'&nbsp;<?php echo empty($fileplan_label)? _FILEPLAN : $fileplan_label;?>',
											'items':[
													<?php
													for($i=0; $i < count($level_1);$i++) {
														?>
														{
														'id' : '<?php functions::xecho($level_1[$i]['id']);?>',
														'title' : '<?php echo addslashes($level_1[$i]['tooltip_value']);?>',
														'canhavechildren' : true,
														'txt' : '<?php echo "&nbsp;".addslashes($level_1[$i]['label_value']);?>',
														'style': 'tree_branch'
														
														},
														<?php
													}
													?>
												]
											}
										];
							tree = new TafelTree('tree_fileplan', struct, {
								'generate' : true,
								'imgBase' : '<?php echo $_SESSION['config']['businessappurl'];?>tools/tafelTree/imgs/',
								'defaultImg' : 'position.gif',
								// 'defaultImgOpen' : 'position_on.gif',
								'defaultImgClose' : 'position.gif',
								"defaultImgOpenSelected" : "position_on.gif",
								"defaultImgCloseSelected" : "position_on.gif",
								'onOpenPopulate' : [funcOpen, '<?php echo $_SESSION['config']['businessappurl'];
												?>index.php?display=true&module=fileplan&page=positions_tree_childs&origin=admin&fileplan_id=<?php 
												functions::xecho($fileplan_id);?>']
							});
						}
					</script>
					<div id="tree_fileplan"></div>
				</td>
			</tr>
		</table>
		</div>
	</div>
<?php
} else {
	echo '<script type="text/javascript">window.top.location.href=\'' 
			. $_SESSION['config']['businessappurl']
			. 'index.php?page=fileplan_admin&module=fileplan&load\';</script>';
	exit;
} 
