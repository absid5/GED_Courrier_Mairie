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
* @brief    Script to handle ajax request for fileplan
*
* @file     fileplan_ajax_script.php
* @author   Yves Christian Kpakpo <dev@maarch.org>
* @date     $date$
* @version  $Revision$
* @ingroup  fileplan
*/

require_once "core" . DIRECTORY_SEPARATOR . "class". DIRECTORY_SEPARATOR ."class_request.php";
require_once "core" . DIRECTORY_SEPARATOR . "class". DIRECTORY_SEPARATOR ."class_history.php";
require_once "modules" . DIRECTORY_SEPARATOR . "fileplan" . DIRECTORY_SEPARATOR
    . "class" . DIRECTORY_SEPARATOR
    . "class_modules_tools.php";

$core_tools = new core_tools();
$db 		= new Database();
$hist 		= new history();
$fileplan   = new fileplan();

$core_tools->load_lang();

$status = 0;
$error = $content = $js = '';

$positions_array = array();

if (isset($_REQUEST['mode']) && !empty($_REQUEST['mode'])) {
    $mode = $_REQUEST['mode'];
} else {
    $error = _ERROR_IN_POSITIONS_FORM_GENERATION;
    $status = 1;
}

//Keep some origin parameters
$parameters = '';
if (isset($_REQUEST['order']) && !empty($_REQUEST['order'])) {
    $parameters .= '&order='.$_REQUEST['order'];
    if (isset($_REQUEST['order_field']) && !empty($_REQUEST['order_field'])) $parameters 
		.= '&order_field='.$_REQUEST['order_field'];
}
if (isset($_REQUEST['what']) && !empty($_REQUEST['what'])) $parameters .= '&what='.$_REQUEST['what'];
if (isset($_REQUEST['template']) && !empty($_REQUEST['template'])) $parameters .= '&template='.$_REQUEST['template'];
if (isset($_REQUEST['start']) && !empty($_REQUEST['start'])) $parameters .= '&start='.$_REQUEST['start'];

//Path to actual script
$path_to_script = $_SESSION['config']['businessappurl']
	."index.php?display=true&module=fileplan&page=fileplan_ajax_script".$parameters;

//Keep the origin to reload the origin list
$list_origin = $refresh = $origin = '';
if (isset($_REQUEST['origin']) && !empty($_REQUEST['origin'])) {
    //
    $origin = $_REQUEST['origin'];
    
	if ($origin == "search") {
        //From search result
        $list_origin = "loadList('".$_SESSION['config']['businessappurl']
            ."index.php?page=documents_list_mlb_search_adv&dir=indexing_searching&display=true&load"
            .$parameters."');";
    } elseif ($origin == "basket") {
        //From basket
        $list_origin = "loadList('".$_SESSION['current_basket']['page_no_frame']
			."&display=true".$parameters."', 'divList', true);";
    } elseif ($origin == "fileplan") {
        //From fileplan menu
        $old_id = '';
        if (isset($_REQUEST['actual_position_id']) && !empty($_REQUEST['actual_position_id'])) {
            //Add the fileplan ID and other parameters to reload the exact list
            $old_id = '&id='.$_REQUEST['fileplan_id'].'@@'.$_REQUEST['actual_position_id'].'';
        }
        $list_origin = "loadList('".$_SESSION['config']['businessappurl']
                ."index.php?display=true&module=fileplan&page=positions_documents_list"
                .$old_id.$parameters."', 'list_doc', true);";
    }
}

switch ($mode) {
	case 'addFileplan':
		//Form
		$content .= '<form name="formFileplan" id="formFileplan" '
			.'method="post" action="#" class="forms">';
		$content .= '<input type="hidden" name="fileplan_id" value="'.$fileplan_id.'" />';
		$content .= '<h2>'._ADD_FILEPLAN.'</h2>';
		$content .= '<div style="height:135px; overflow-x:hidden; overflow-y:auto;">';
		//Name
		$content .='<p>';
		$content .='<label nowrap>'._FILEPLAN_NAME.': </label>';
		$content .='<input name="fileplan_label" type="text" '
			.'id="fileplan_label" class="fileplan_position" value="'
			.'" /><span class="red_asterisk"><i class="fa fa-star"></i></span>';
		$content .='</p>';
		//Entity if needed
		/*
		if ($core_tools->test_service('admin_fileplan', 'fileplan', false)) {
			//If entities module
			if ($core_tools->is_module_loaded('entities')) {
				require_once 'modules' . DIRECTORY_SEPARATOR . 'entities'
					. DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
					. 'class_manage_entities.php';
				$ent = new entity();
				$allEntitiesTree= array();
				$allEntitiesTree = $ent->getShortEntityTreeAdvanced($allEntitiesTree, 'all');
				// print_r($allEntitiesTree);
				$content .='<p>';
				$content .='<label >'._ENTITY.': </label>';
				$content .='<select name="entity_id" id="entity_id" class="fileplan_position">';
				$content .= '<option value="">' . _CHOOSE_DEPARTMENT . '</option>';
				for($i=0; $i < count($allEntitiesTree); $i++) {
					//Is keyword
					if (!$allEntitiesTree[$i]['KEYWORD']) {
						$content .='<option value="'.$allEntitiesTree[$i]['ID'].'"';
						//Is disable ?
						if($allEntitiesTree[$i]['DISABLED'])
							$content .= ' disabled="disabled" class="disabled_entity"';
            
						$content .='>'.$db->show_string($allEntitiesTree[$i]['SHORT_LABEL']).'</option>';
					}
				}
				$content .='</select><span class="red_asterisk">*</span>';
				$content .='</p>';
			}
		}*/
		//
		$content .='<br/><p>';
		$content .='<label >'._IS_SERIAL_ID.': </label>';
		$content .='<input name="is_serial" type="radio" id="is_serial" value="Y" checked="ckecked" />'._YES;
		$content .='<input name="is_serial" type="radio" id="is_serial" value="N" />'._NO;
		$content .='<span class="red_asterisk">*</span>';
		$content .='</p>';
		$content .= '</div>';
		//Buttons
		$content .='<hr />';
		$content .='<div align="center">';
		$content .=' <input type="button" name="valid" value="&nbsp;'._VALIDATE
				 .'&nbsp;" id="valid" class="button" onclick="validFileplanForm(\''
				 .$path_to_script.'&origin=admin&mode=saveFileplan\', \'formFileplan\');" />&nbsp;';
		$content .='<input type="button" name="cancel" id="cancel" class="button"  value="'
			._CANCEL.'" onclick="destroyModal(\'modal_fileplan\');"/>';
		$content .='</div">';
		$content .= '</form>';
	break;
	case 'saveFileplan':
		if (strlen(trim($_REQUEST['fileplan_label'])) > 0) {
			//Init
            $user_id = NULL;
            $entity_id = NULL;
			
			//Get fileplan scope
			if ($origin == "admin" 
				&& $core_tools->test_service('admin_fileplan', 'fileplan', false)
			) {
				/*
				if (isset($_REQUEST['entity_id']) && !empty($_REQUEST['entity_id'])) {
					 $entity_id = "'".$_REQUEST['entity_id']."'";
				} else {
					$error = functions::wash_html(_ENTITY.' '._IS_EMPTY.'!','NONE');
					$status = 1;
				}*/
			} elseif ($origin == "manage") {
				$user_id = $_SESSION['user']['UserId'];
			}
			
			if ($status <> 1) {
				//Insert data
				$fileplan_label = $_REQUEST['fileplan_label'];
				$stmt = $db->query("INSERT INTO ".FILEPLAN_TABLE
					. " (fileplan_label, user_id, entity_id, is_serial_id, enabled)" 
					. " VALUES (?,?,?,?,?)"
				,array($fileplan_label,$user_id,$entity_id,$_REQUEST['is_serial'],'Y'));
			
				//History
				if ($_SESSION['history']['fileplanadd']) {
					//Last insert ID from sequence
					$id = $db->lastInsertId('fp_fileplan_fileplan_id_seq');
					//Add to history
					$hist->add(
						FILEPLAN_TABLE, $id, "ADD", 'fileplanadd', _FILEPLAN_ADDED 
						. ": ".$fileplan_label." (" . $id . ")",
						$_SESSION['config']['databasetype'], 'fileplan'
					);
				}
				
				//Return javascript
				if ($origin == "admin") {
					//From admin
					$js .= "destroyModal('modal_fileplan');";
					$js .= "loadList('"
						.$_SESSION['config']['businessappurl']
						."index.php?page=fileplan_admin&module=fileplan&display=true"
						.$parameters."');";
				} elseif ($origin == "manage") {
					//From manage
					$js .= "window.top.location.href='"
						.$_SESSION['config']['businessappurl']
						."index.php?page=fileplan_managment&module=fileplan&reinit=true&load';";
				}
			}
		} else {
            $error = functions::wash_html(_FILEPLAN_NAME.' '._IS_EMPTY.'!','NONE');
            $status = 1;
        }
	break;
	case 'upFileplan':
		if (isset($_REQUEST['fileplan_id']) && !empty($_REQUEST['fileplan_id'])) {
			//Get fileplan ID
			$fileplan_id = $_REQUEST['fileplan_id'];
			$fileplan_array = $fileplan->getFileplan($fileplan_id, false);
			//
			$content .= '<form name="formFileplan" id="formFileplan" '
				.'method="post" action="#" class="forms">';
			$content .= '<input type="hidden" name="fileplan_id" value="'.$fileplan_array['ID'].'" />';
			$content .= '<h2>'._EDIT_FILEPLAN.'</h2>';
			$content .= '<div style="height:135px; overflow-x:hidden; overflow-y:auto;">';
			//Name
			$content .='<p>';
			$content .='<label nowrap>'._FILEPLAN_NAME.': </label>';
			$content .='<input name="fileplan_label" type="text" '
				.'id="fileplan_label" class="fileplan_position" value="'
				.$fileplan_array['LABEL']
				.'" /><span class="red_asterisk"><i class="fa fa-star"></i></span>';
			$content .='</p>';
			//Entity if needed
			/*
			if ($core_tools->test_service('admin_fileplan', 'fileplan', false)) {
				//If entities module
				if ($core_tools->is_module_loaded('entities')) {
					require_once 'modules' . DIRECTORY_SEPARATOR . 'entities'
						. DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR
						. 'class_manage_entities.php';
					$ent = new entity();
					$allEntitiesTree= array();
					$allEntitiesTree = $ent->getShortEntityTreeAdvanced($allEntitiesTree, 'all');
					$content .='<p>';
					$content .='<label >'._ENTITY.': </label>';
					$content .='<select name="entity_id" id="entity_id" class="fileplan_position">';
					$content .= '<option value="">' . _CHOOSE_DEPARTMENT . '</option>';
					for($i=0; $i < count($allEntitiesTree); $i++) {
						//Is keyword?
						if (!$allEntitiesTree[$i]['KEYWORD']) {
							$content .='<option value="'.$allEntitiesTree[$i]['ID'].'"';
							//Is disable?
							if($allEntitiesTree[$i]['DISABLED'])
								$content .= ' disabled="disabled" class="disabled_entity"';
							//Is Selected?
							if ($fileplan_array['ENTITY'] == $allEntitiesTree[$i]['ID'])
								$content .= ' selected="selected"';
							$content .='>'.$db->show_string($allEntitiesTree[$i]['SHORT_LABEL']).'</option>';
						}
					}
					$content .='</select><span class="red_asterisk">*</span>';
					$content .='</p>';
				}
			}*/
			//If fileplan has position no possibility to change type
			if ($fileplan->fileplanHasPositions($fileplan_array['ID']) === false){
				$checkedYes = $checkedNo = '';
				if($fileplan_array['IS_SERIAL'] == 'Y') {
					$checkedYes = 'checked="ckecked"';
				} else {
					$checkedNo = 'checked="ckecked"';
				}
				$content .='<br/><p>';
				$content .='<label >'._IS_SERIAL_ID.': </label>';
				$content .='<input name="is_serial" type="radio" id="is_serial" value="Y" '.$checkedYes.' />'._YES;
				$content .='<input name="is_serial" type="radio" id="is_serial" value="N" '.$checkedNo.' />'._NO;
				$content .='<span class="red_asterisk"><i class="fa fa-star"></i></span>';
				$content .='</p>';
			}
			$content .= '</div>';
			//Buttons
			$content .='<hr />';
			$content .='<div align="center">';
			$content .=' <input type="button" name="valid" value="&nbsp;'._VALIDATE
					 .'&nbsp;" id="valid" class="button" onclick="validFileplanForm(\''
					 .$path_to_script.'&origin='.$origin.'&mode=updateFileplan\', \'formFileplan\');" />&nbsp;';
			$content .='<input type="button" name="cancel" id="cancel" class="button"  value="'
				._CANCEL.'" onclick="destroyModal(\'modal_fileplan\');"/>';
			$content .='</div">';
			$content .= '</form>';
		} else {
            $error = functions::wash_html(_FILEPLAN_ID.' '._IS_EMPTY.'!','NONE');
            $status = 1;
        }
	break;
	case 'updateFileplan':
		if (isset($_REQUEST['fileplan_id']) && !empty($_REQUEST['fileplan_id'])) {
			//Get fileplan ID
			$fileplan_id = $_REQUEST['fileplan_id'];
			//Get fileplan name
			if (strlen(trim($_REQUEST['fileplan_label'])) > 0) {
				//Init
				$user_id = 'NULL';
				$entity_id = 'NULL';
				
				//Get fileplan scope
				if ($origin == "admin" 
					&& $core_tools->test_service('admin_fileplan', 'fileplan', false)
				) {
					/*
					if (isset($_REQUEST['entity_id']) && !empty($_REQUEST['entity_id'])) {
						 $entity_id = "'".$_REQUEST['entity_id']."'";
					} else {
						$error = functions::wash_html(_ENTITY.' '._IS_EMPTY.'!','NONE');
						$status = 1;
					}*/
				} elseif ($origin == "manage") {
					$user_id = "'".$_SESSION['user']['UserId']."'";
				}
				
				if ($status <> 1) {
					//Update data
					$fileplan_label = $_REQUEST['fileplan_label'];
					$stmt = $db->query(
						"UPDATE ".FILEPLAN_TABLE . " SET fileplan_label = ?" 
						. ", user_id = ?" 
						. ", entity_id = ?"
						. ", is_serial_id = ?"
						. " WHERE fileplan_id = ?"
					,array($fileplan_label,$user_id,$entity_id,$_REQUEST['is_serial'],$fileplan_id));

					//History
					if ($_SESSION['history']['fileplanup']) {
						//Add to history
						$hist->add(
							FILEPLAN_TABLE, $fileplan_id, "UP", 'fileplanup', 
							_FILEPLAN_UPDATED . ": ".$fileplan_label." (" . $fileplan_id . ")",
							$_SESSION['config']['databasetype'], 'fileplan'
						);
					}
					
					//Return javascript
					$js .= "destroyModal('modal_fileplan');";
					if ($origin == "admin") {
						//From admin
						$js .= "loadList('"
							.$_SESSION['config']['businessappurl']
							."index.php?page=fileplan_admin&module=fileplan&display=true"
							.$parameters."');";
					} elseif ($origin == "manage") {
						//From manage
						$js .= "window.top.location.href='"
							.$_SESSION['config']['businessappurl']
							."index.php?page=fileplan_managment&module=fileplan&reinit=true&load';";
					}
				}
			} else {
				$error = functions::wash_html(_FILEPLAN_NAME.' '._IS_EMPTY.'!','NONE');
				$status = 1;
			}
		} else {
            $error = functions::wash_html(_FILEPLAN_ID.' '._IS_EMPTY.'!','NONE');
            $status = 1;
        }
	break;
	case 'delFileplan':
		if (isset($_REQUEST['fileplan_id']) && !empty($_REQUEST['fileplan_id'])) {
			//Get fileplan ID
			$fileplan_id = $_REQUEST['fileplan_id'];
			//
			$fileplan_array = $fileplan->getFileplan($fileplan_id, false);
			
			$content .= '<form name="formFileplan" id="formFileplan" '
				.'method="post" action="#" class="forms">';
			$content .= '<input type="hidden" name="fileplan_id" value="'.$fileplan_array['ID'].'" />';
			$content .= '<h2>'._DELETE_FILEPLAN.'</h2>';
            $content .= _REMOVE_FILEPLAN_INFOS_1;
            $content .= '<br/>';
            $content .= _REMOVE_FILEPLAN_INFOS_2;
            $content .= '<br/><br/>';
            $content .= '<div style="height:225px; overflow-x:hidden; overflow-y:auto; border:1px solid #CCCCCC;">';
            
            //Position tree array
			$content .='<b>'.$fileplan_array['LABEL'].'</b><br/>';//Init with fileplan
			//Get positions tree
			$positions_array = $fileplan->getPositionsTree($fileplan_array['ID'], $positions_array);
			for($i=0; $i < count($positions_array); $i++) {
				//Is enable ?
				if ($fileplan->isEnable($fileplan_array['ID'], $positions_array[$i]['ID'])) { 
					$content .='<b>'.$positions_array[$i]['LABEL']
					.'</b>&nbsp;&nbsp;&nbsp;<em>('.$positions_array[$i]['COUNT_DOCUMENT']
					.' '._DOC_IN_FILEPLAN.')</em><br/>';
				}
			}
            $content .= '</div>';
			//Buttons
			// $content .='<hr />';
			$content .='<div align="center">';
			$content .=' <input type="button" name="valid" value="&nbsp;'._DELETE
					 .'&nbsp;" id="valid" class="button" onclick="validFileplanForm(\''
					 .$path_to_script.'&origin='.$origin.'&mode=deleteFileplan\', \'formFileplan\');" />&nbsp;';
			$content .='<input type="button" name="cancel" id="cancel" class="button"  value="'
				._CANCEL.'" onclick="destroyModal(\'modal_fileplan\');"/>';
			$content .='</div">';
			$content .= '</form>';
		} else {
            $error = functions::wash_html(_FILEPLAN_ID.' '._IS_EMPTY.'!','NONE');
            $status = 1;
        }
	break;
	case 'deleteFileplan':
		if (isset($_REQUEST['fileplan_id']) && !empty($_REQUEST['fileplan_id'])) {
			
			$fileplan_id = $_REQUEST['fileplan_id'];
			//Get fipeplan
			$fileplan_array = $fileplan->getFileplan($fileplan_id, false);

			//Check ID
            if (isset($fileplan_array['ID']) && $fileplan_id == $fileplan_array['ID']) {

				//Delete linked documents                
                $stmt = $db->query(
                    "DELETE FROM " 
                    . FILEPLAN_RES_POSITIONS_TABLE . " WHERE fileplan_id = ?" 
                ,array($fileplan_array['ID']));  
                //Delete position
                $stmt = $db->query(
                    "DELETE FROM " 
                    . FILEPLAN_POSITIONS_TABLE . " WHERE fileplan_id = ?" 
                ,array($fileplan_array['ID']));
				//Delete fileplan
                $stmt = $db->query(
                    "DELETE FROM " 
                    . FILEPLAN_TABLE . " WHERE fileplan_id = ?"
                ,array($fileplan_array['ID']));
					
				//History
				if ($_SESSION['history']['fileplandel']) {
					//Add to history
					$hist->add(
						FILEPLAN_TABLE, $fileplan_array['ID'], "DEL", 'fileplandel', _FILEPLAN_DELETED 
						. ': '.functions::wash_html($fileplan_array['LABEL']).' (' . $fileplan_array['ID'] . ')',
						$_SESSION['config']['databasetype'], 'fileplan'
					);
				}
            } else {
                $error = functions::wash_html($fileplan_id.': '._FILEPLAN_NOT_EXISTS.'!','NONE');
                $status = 1;
            }

			//Return javascript
			$js .= "destroyModal('modal_fileplan');";
			if ($origin == "admin") {
				//From admin
				$js .= "loadList('"
					.$_SESSION['config']['businessappurl']
					."index.php?page=fileplan_admin&module=fileplan&display=true"
					.$parameters."');";
			} elseif ($origin == "manage") {
				//From manage
				$js .= "window.top.location.href='"
					.$_SESSION['config']['businessappurl']
					."index.php?page=fileplan&module=fileplan&reinit=true&load';";
			}
	} else {
            $error = functions::wash_html(_FILEPLAN_ID.' '._IS_EMPTY.'!','NONE');
            $status = 1;
        }
	break;
	case 'disFileplan':
		if (isset($_REQUEST['fileplan_id']) && !empty($_REQUEST['fileplan_id'])) {
			//Get fileplan ID
			$fileplan_id = $_REQUEST['fileplan_id'];
				
			//Get fipeplan
			$fileplan_array = $fileplan->getFileplan($fileplan_id, false);

			//Check ID
            if (isset($fileplan_array['ID']) && $fileplan_id == $fileplan_array['ID']) {

				//Disable fileplan positions
				$stmt = $db->query(
					"UPDATE ".FILEPLAN_POSITIONS_TABLE
					. " SET enabled = ? WHERE fileplan_id = ?"
					. " AND enabled = ?"
				,array('N',$fileplan_id,'Y'));
					
				//Disable fileplan
                $stmt = $db->query(
                    "UPDATE " . FILEPLAN_TABLE 
					. " SET enabled = ? WHERE fileplan_id = ?" 
                    . " AND enabled = ?"
                ,array('N',$fileplan_array['ID'],'Y'));
				
				//History
				if ($_SESSION['history']['fileplandis']) {
					//Add to history
					$hist->add(
						FILEPLAN_TABLE, $fileplan_array['ID'], "UP", 'fileplandis',
						_FILEPLAN_DISABLED .': '.$fileplan_array['LABEL']
						.' (' . $fileplan_array['ID'] . ')',
						$_SESSION['config']['databasetype'], 'fileplan'
					);
				}

				//Reload list
				if ($origin == "admin") {
					//From admin
					$js .= "loadList('"
						.$_SESSION['config']['businessappurl']
						."index.php?page=fileplan_admin&module=fileplan&display=true"
						.$parameters."');";
				} elseif ($origin == "manage") {
					//From manage
					$js .= "window.top.location.href='"
						.$_SESSION['config']['businessappurl']
						."index.php?page=fileplan_managment&module=fileplan&reinit=true&load';";
				}
			} else {
                $error = functions::wash_html($fileplan_id.': '._FILEPLAN_NOT_EXISTS.'!','NONE');
                $status = 1;
            }
		} else {
            $error = functions::wash_html(_FILEPLAN_ID.' '._IS_EMPTY.'!','NONE');
            $status = 1;
        }
		
    break;
    case 'enaFileplan':
		if (isset($_REQUEST['fileplan_id']) && !empty($_REQUEST['fileplan_id'])) {
			//Get fileplan ID
			$fileplan_id = $_REQUEST['fileplan_id'];
			
			//Get fipeplan
			$fileplan_array = $fileplan->getFileplan($fileplan_id, false);

			//Check ID
            if (isset($fileplan_array['ID']) && $fileplan_id == $fileplan_array['ID']) {

				//Disable fileplan positions
				$stmt = $db->query(
					"UPDATE ".FILEPLAN_POSITIONS_TABLE
					. " SET enabled = ? WHERE fileplan_id = ?"
					. " AND enabled = ?"
				,array('N',$fileplan_id,'Y'));
					
				//Disable fileplan
                $stmt = $db->query(
                    "UPDATE " . FILEPLAN_TABLE 
					. " SET enabled = ? WHERE fileplan_id = ?" 
                    . " AND enabled = ?"
                ,array('Y',$fileplan_array['ID'],'N'));
				
				//History
				if ($_SESSION['history']['fileplanena']) {
					//Add to history
					$hist->add(
						FILEPLAN_TABLE, $fileplan_array['ID'], "UP", 'fileplanena',
						_FILEPLAN_ENABLED .': '.$fileplan_array['LABEL']
						.' (' . $fileplan_array['ID'] . ')',
						$_SESSION['config']['databasetype'], 'fileplan'
					);
				}

				//Reload list
				if ($origin == "admin") {
					//From admin
					$js .= "loadList('"
						.$_SESSION['config']['businessappurl']
						."index.php?page=fileplan_admin&module=fileplan&display=true"
						.$parameters."');";
				} elseif ($origin == "manage") {
					//From manage
					$js .= "window.top.location.href='"
						.$_SESSION['config']['businessappurl']
						."index.php?page=fileplan_managment&module=fileplan&reinit=true&load';";
				}				
			} else {
                $error = functions::wash_html($fileplan_id.': '._FILEPLAN_NOT_EXISTS.'!','NONE');
                $status = 1;
            }
		} else {
            $error = functions::wash_html(_FILEPLAN_ID.' '._IS_EMPTY.'!','NONE');
            $status = 1;
        }
    break;
    case 'addPosition':
		if (isset($_REQUEST['fileplan_id']) && !empty($_REQUEST['fileplan_id'])) {
			//Get fileplan ID
			$fileplan_id = $_REQUEST['fileplan_id'];
			
			//Build form position
			$content .= '<form name="formPosition" id="formPosition" method="post" action="#" class="forms">';
			$content .= '<input type="hidden" name="fileplan_id" value="'.$fileplan_id.'" />';
			$content .= '<h2>'._NEW_POSITION.'</h2>';
			//If fileplan is not serial, show position ID field
			if ($fileplan->isSerialFileplan($fileplan_id) === false){
				$content .= '<label>'._POSITION_ID.' : </label><br/>';
				$content .= '<input type="text" name="position_id"  id="position_id" '
				.'value="" class="medium" /><br/><br/>';
			}
			//Position label
			$content .= '<label>'._POSITION_NAME.': </label><br/>';
			$content .= '<input type="text" name="position_label"  id="position_label" '
				.'value="" class="fileplan_position" /><br/><br/>';
			//Nest position under parent
			$content .= _NEST_POSITION_UNDER.': <br/>';
			$content .='<select name="parent_id" id="parent_id" class="fileplan_position">'; 
			$content .='<option value="">'._CHOOSE_PARENT_POSITION.'</option>';
			//Get positions tree
			$positions_array = $fileplan->getPositionsTree($fileplan_id, $positions_array);
			//Init with fileplan
			(count($positions_array) == 0)? $rootSelected = ' selected="selected"' : $rootSelected = '';
			$fileplan_array = $fileplan->getFileplan($fileplan_id);
			$content .='<option value="'.$fileplan_array['ID'].'"'.$rootSelected.'>'
				.$fileplan_array['LABEL'].'</option>';
			//Show positions
			for($i=0; $i < count($positions_array); $i++) {
				//Is enable ?
				if ($fileplan->isEnable($fileplan_id, $positions_array[$i]['ID'])) { 
					$content .='<option value="'.$positions_array[$i]['ID'].'" >'
						.$positions_array[$i]['LABEL'].'</option>';
				}
			}
			$content .='</select>';
			//Buttons
			$content .='<hr />';
			$content .='<div align="center">';
			$content .=' <input type="button" name="valid" value="&nbsp;'._ADD_POSITION
					 .'&nbsp;" id="valid" class="button" onclick="validFileplanForm(\''
					 .$path_to_script.'&origin='.$origin.'&mode=addedPosition\', \'formPosition\');" />&nbsp;';
			$content .='<input type="button" name="cancel" id="cancel" class="button"  value="'
				._CANCEL.'" onclick="destroyModal(\'modal_fileplan\');"/>';
			$content .='</div">';
			$content .= '</form>';
        } else {
            $error = functions::wash_html(_FILEPLAN_ID.' '._IS_EMPTY.'!','NONE');
            $status = 1;
        }
    break;
    case 'addedPosition':
		if (isset($_REQUEST['fileplan_id']) && !empty($_REQUEST['fileplan_id'])) {
			//Get fileplan ID
			$fileplan_id = $_REQUEST['fileplan_id'];
			//Get position label
			if (strlen(trim($_REQUEST['position_label'])) > 0) {
				//If is not serial fileplan
				if ($fileplan->isSerialFileplan($fileplan_id) === false){
					if (empty($_REQUEST['position_id'])) {
							$error = functions::wash_html(_POSITION_ID.' '._IS_EMPTY.'!','NONE');
							$status = 1;
					} else {
					   $position_id = $_REQUEST['position_id'];
					}
				}  else {

					$position_id = $db->lastInsertId('fp_fileplan_positions_position_id_seq');
					$position_id = $position_id +1;

					if (empty($position_id)) {
							$error = functions::wash_html(_ERROR_DURING_POSITION_ID_GENERATION.'!','NONE');
							$status = 1;
					}
				}
				//Nested?
				if (empty($_REQUEST['parent_id'])) {
						$error .= functions::wash_html(_CHOOSE_PARENT_POSITION.'!','NONE');
						$status = 1;
				} else {
					($_REQUEST['parent_id'] == '##ROOT##')?
						$parent_id = NULL : $parent_id = $_REQUEST['parent_id'];
				}

				//Add position
				if ( $status <> 1) {
					//If position id already exists
					if($fileplan->positionAlreadyExists($fileplan_id, $position_id) === true) {
							$error = functions::wash_html(_POSITION_ALREADY_EXISTS.': '.$position_id.'!','NONE');
							$status = 1;
					} else {
						$position_label = $_REQUEST['position_label'];
						$stmt = $db->query(
							"INSERT INTO ".FILEPLAN_POSITIONS_TABLE
							. " (position_label, parent_id, fileplan_id, enabled) VALUES (?,?,?,?)"
						,array($position_label,$parent_id,$fileplan_id,'Y'));
							
						//History
						if ($_SESSION['history']['fileplanadd']) {
							//Add to history
							$hist->add(
								FILEPLAN_POSITIONS_TABLE, $position_id, "ADD", 'fileplanadd',
								_POSITION_ADDED . ': '.$position_label.' (' . $position_id . ')',
								$_SESSION['config']['databasetype'], 'fileplan'
							);
						}
						//Reload and show message
						$js .= "destroyModal('modal_fileplan');";
						if ($origin == "admin") {
							//From admin
							$js .= "loadList('"
								.$_SESSION['config']['businessappurl']
								."index.php?page=fileplan_admin_managment&module=fileplan&"
								."fileplan_id=".$fileplan_id."&display=true".$parameters."');";
						} elseif ($origin == "manage") {
							//From manage
							$js .= "loadList('".$_SESSION['config']['businessappurl']
									."index.php?display=true&page=fileplan_managment"
									."&module=fileplan".$parameters."');";
						}
						$js .= "window.top.$('main_info').innerHTML = '"._POSITION_ADDED.': '
							.$_REQUEST['position_label']."';";
					}
				}
			} else {
				$error = functions::wash_html(_POSITION_NAME.' '._IS_EMPTY.'!','NONE');
				$status = 1;
			}
		} else {
            $error = functions::wash_html(_FILEPLAN_ID.' '._IS_EMPTY.'!','NONE');
            $status = 1;
        }
    break;
    case 'upPosition':
		if (isset($_REQUEST['fileplan_id']) && !empty($_REQUEST['fileplan_id'])) {
			//Get fileplan ID
			$fileplan_id = $_REQUEST['fileplan_id'];
			
			//Get position ID
			if (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {
				$position_id = $_REQUEST['id'];
				
				//Check if position ID exists
				if($fileplan->positionAlreadyExists($fileplan_id, $position_id)) {
				
					//Get array of actual position
					$positionArray = $fileplan->getPosition($fileplan_id, $position_id);
					
					//Form position
					$content .= '<form name="formPosition" id="formPosition" method="post" action="#" class="forms">';
					$content .= '<input type="hidden" name="fileplan_id" value="'.$fileplan_id.'" />';
					$content .= '<input type="hidden" name="id" value="'.$position_id.'" />';
					$content .= '<h2>'._EDIT_POSITION.'</h2>';
					//If fileplan is not serial, show position ID field
					if ($fileplan->isSerialFileplan($fileplan_id) === false){
						$content .= '<label>'._POSITION_ID.' : </label><br/>';
						$content .= '<input type="text" name="position_id"  id="position_id" '
						.'value="'.$positionArray[0]['ID'].'" class="medium" /><br/><br/>';
					}
					//Position label
					$content .= '<label>'._POSITION_NAME.': </label><br/>';
					$content .= '<input type="text" name="position_label"  id="position_label" '
						.'value="'.$positionArray[0]['LABEL'].'" class="fileplan_position" /><br/><br/>';
					//Nest position under parent
					$content .= _NEST_POSITION_UNDER.': <br/>';
					$content .='<select name="parent_id" id="parent_id" class="fileplan_position">'; 
					$content .='<option value="">'._CHOOSE_PARENT_POSITION.'</option>';
					//Init with fileplan
					$fileplan_array = $fileplan->getFileplan($fileplan_id);
					//Selected?
					(empty($positionArray[0]['PARENT_ID']))? $rootSelected = ' selected="selected"' : $rootSelected = '';
					$content .='<option value="'.$fileplan_array['ID'].'" '.$rootSelected.'>'
						.$fileplan_array['LABEL'].'</option>';
					//Get positions tree
					$positions_array = $fileplan->getPositionsTree($fileplan_id, $positions_array);
					for($i=0; $i < count($positions_array); $i++) {
						//Is enable ?
						if ($fileplan->isEnable($fileplan_id, $positions_array[$i]['ID'])) { 
							//Is Selected?
							($positionArray[0]['PARENT_ID'] == $positions_array[$i]['ID'])?
								$selected = ' selected="selected"' : $selected = '';
							$content .='<option value="'.$positions_array[$i]['ID'].'" '.$selected.'>'
								.$positions_array[$i]['LABEL'].'</option>';
						}
					}
					$content .='</select>';
					//Buttons
					$content .='<hr />';
					$content .='<div align="center">';
					$content .=' <input type="button" name="valid" value="&nbsp;'._EDIT_POSITION_SHORT
							 .'&nbsp;" id="valid" class="button" onclick="validFileplanForm(\''
							 .$path_to_script.'&origin='.$origin.'&mode=updatePosition\', \'formPosition\');" />&nbsp;';
					$content .='<input type="button" name="cancel" id="cancel" class="button"  value="'
						._CANCEL.'" onclick="destroyModal(\'modal_fileplan\');"/>';
					$content .='</div">';
					$content .= '</form>';

				} else {
					$error = functions::wash_html($position_id.': '._POSITION_NOT_EXISTS.'!','NONE');
					$status = 1;
				}
			} else {
				$error = functions::wash_html(_POSITION_ID.' '._IS_EMPTY.'!','NONE');
				$status = 1;
			}
		} else {
            $error = functions::wash_html(_FILEPLAN_ID.' '._IS_EMPTY.'!','NONE');
            $status = 1;
        }
    break;
    case 'updatePosition':
		if (isset($_REQUEST['fileplan_id']) && !empty($_REQUEST['fileplan_id'])) {
			//Get fileplan ID
			$fileplan_id = $_REQUEST['fileplan_id'];
			
			//Get position ID
			if (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {
				$old_position_id = $_REQUEST['id'];
				
				//Get position label
				if (strlen(trim($_REQUEST['position_label'])) > 0) {
				
					//If is not serial fileplan
					if ($fileplan->isSerialFileplan($fileplan_id) === false){
						if (empty($_REQUEST['position_id'])) {
								$error = functions::wash_html(_POSITION_ID.' '._IS_EMPTY.'!','NONE');
								$status = 1;
						} else {
						   $position_id = $_REQUEST['position_id'];
						}
					} else {
						$position_id = $old_position_id;
					}
					
					//Nested?
					if (empty($_REQUEST['parent_id'])) {
							$error .= functions::wash_html(_CHOOSE_PARENT_POSITION.'!','NONE');
							$status = 1;
					} else {
						($_REQUEST['parent_id'] == '##ROOT##')?
							$parent_id = NULL : $parent_id = $_REQUEST['parent_id'];
					}
					
					//Update position
					if ( $status != 1) {
					
						//Check if old position ID exists
						if($fileplan->positionAlreadyExists($fileplan_id, $old_position_id)) {

							//If new position id already exists
							if($fileplan->positionAlreadyExists($fileplan_id, $position_id) && ($old_position_id <> $position_id)) {
									$error = functions::wash_html(_POSITION_ALREADY_EXISTS.': '.$position_id.'!','NONE');
									$status = 1;
							} else {
								$position_label = $_REQUEST['position_label'];
								$stmt = $db->query(
									"UPDATE ".FILEPLAN_POSITIONS_TABLE
									. " SET position_id = ?"
									. ", position_label = ?"
									. ", parent_id = ?"
									. " WHERE fileplan_id = ?"
									. " AND position_id = ?"
									,array($position_id,$position_label,$parent_id,$fileplan_id,$old_position_id));
									
								//History
								if ($_SESSION['history']['fileplanup']) {
									//Add to history
									($position_id <> $old_position_id)? $info_hist = $position_label.' (' . _POSITION_ID . ': ' 
										. $old_position_id . ' '._TO.': '.$position_id
										. ')' : $info_hist = $position_label.' (' . $position_id . ')';
									$hist->add(
										FILEPLAN_POSITIONS_TABLE, $position_id, "UP", 'fileplanup',
										_POSITION_UPDATED . ': '.$info_hist,
										$_SESSION['config']['databasetype'], 'fileplan'
									);
								}
								//Reload and show message
								$js .= "destroyModal('modal_fileplan');";
								if ($origin == "admin") {
									//From admin
									$js .= "loadList('"
										.$_SESSION['config']['businessappurl']
										."index.php?page=fileplan_admin_managment&module=fileplan&"
										."fileplan_id=".$fileplan_id."&display=true".$parameters."');";
								} elseif ($origin == "manage") {
									//From manage
									$js .= "loadList('".$_SESSION['config']['businessappurl']
											."index.php?display=true&page=fileplan_managment"
											."&module=fileplan".$parameters."');";
								}
								$js .= "window.top.$('main_info').innerHTML = '"._POSITION_UPDATED.': '
									.$_REQUEST['position_label']."';";
							}
						} else {
							$error = functions::wash_html($old_position_id.': '._POSITION_NOT_EXISTS.'!','NONE');
							$status = 1;
						}
					}
				} else {
					$error = functions::wash_html(_POSITION_NAME.' '._IS_EMPTY.'!','NONE');
					$status = 1;
				}
			} else {
				$error = functions::wash_html(_POSITION_ID.' '._IS_EMPTY.'!','NONE');
				$status = 1;
			}
		} else {
            $error = functions::wash_html(_FILEPLAN_ID.' '._IS_EMPTY.'!','NONE');
            $status = 1;
        }
    break;
    case 'delPosition':
		if (isset($_REQUEST['fileplan_id']) && !empty($_REQUEST['fileplan_id'])) {
			//Get fileplan ID
			$fileplan_id = $_REQUEST['fileplan_id'];
			
			//Get position ID
			if (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {
				$position_id = $_REQUEST['id'];
				
				//Check if position ID exists
				if($fileplan->positionAlreadyExists($fileplan_id, $position_id)) {
				
					//Delete window
					$content .= '<form name="formPosition" id="formPosition" method="post" action="#" class="forms">';
					$content .= '<input type="hidden" name="fileplan_id" value="'.$fileplan_id.'" />';
					$content .= '<input type="hidden" name="id" value="'.$position_id.'" />';
					$content .= '<h2>'._REMOVE_POSITION.'</h2>';
					$content .= _REMOVE_POSITIONS_INFOS_1;
					$content .= '<br/>';
					$content .= _REMOVE_POSITIONS_INFOS_2;
					$content .= '<br/><br/>';
					$content .= '<div style="height:290px; overflow-x:hidden; overflow-y:auto; border:1px solid #CCCCCC;">';
					
					//Get array position
					$this_position_array = $fileplan->getPosition($fileplan_id, $position_id);
					//Get position tree
					$positions_array = $fileplan->getPositionsTree($fileplan_id, $positions_array, $this_position_array);
					for($i=0; $i < count($positions_array); $i++) {
						//Is enable ?
						if ($fileplan->isEnable($fileplan_id, $positions_array[$i]['ID'])) { 
							$content .='<b>'.$positions_array[$i]['LABEL']
							.'</b>&nbsp;&nbsp;&nbsp;<em>('.$positions_array[$i]['COUNT_DOCUMENT']
							.' '._DOC_IN_FILEPLAN.')</em><br/>';
						}
					}
					$content .= '</div>';
					
					//Buttons
					// $content .='<hr />';
					$content .='<div align="center">';
					$content .=' <input type="button" name="valid" value="&nbsp;'._DELETE_POSITION
							 .'&nbsp;" id="valid" class="button" onclick="validFileplanForm(\''
							 .$path_to_script.'&origin='.$origin.'&mode=deletePosition\', \'formPosition\');" />&nbsp;';
					$content .='<input type="button" name="cancel" id="cancel" class="button"  value="'
						._CANCEL.'" onclick="destroyModal(\'modal_fileplan\');"/>';
					$content .='</div">';
					$content .= '</form>';

				} else {
					$error = functions::wash_html($position_id.': '._POSITION_NOT_EXISTS.'!','NONE');
					$status = 1;
				}
			} else {
				$error = functions::wash_html(_POSITION_ID.' '._IS_EMPTY.'!','NONE');
				$status = 1;
			}
		} else {
            $error = functions::wash_html(_FILEPLAN_ID.' '._IS_EMPTY.'!','NONE');
            $status = 1;
        }
    break;
    case 'deletePosition':
		if (isset($_REQUEST['fileplan_id']) && !empty($_REQUEST['fileplan_id'])) {
			//Get fileplan ID
			$fileplan_id = $_REQUEST['fileplan_id'];
			
			//Get position ID
			if (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {
				$position_id = $_REQUEST['id'];
				
				//Check if position ID exists
				if($fileplan->positionAlreadyExists($fileplan_id, $position_id)) {
	
					//Get array position
					$this_position_array = $fileplan->getPosition($fileplan_id, $position_id);
					//Get position tree
					$positions_array = $fileplan->getPositionsTree($fileplan_id, $positions_array, $this_position_array);
					
					$position_label = '';
					for($i=0; $i < count($positions_array); $i++) {
						//Kepp label
						$position_label .= functions::wash_html($positions_array[$i]['LABEL']) .'<br/>';
						 
						//Delete linked documents                
						$stmt = $db->query(
							"DELETE FROM " 
							. FILEPLAN_RES_POSITIONS_TABLE . " WHERE fileplan_id = ?" 
							. " AND position_id = ?"
						,array($fileplan_id,$positions_array[$i]['ID']));  
						//Delete position
						$stmt = $db->query(
							"DELETE FROM " 
							. FILEPLAN_POSITIONS_TABLE . " WHERE fileplan_id = ?" 
							. " AND position_id = ?"
						,array($fileplan_id,$positions_array[$i]['ID']));
						
						//History
						if ($_SESSION['history']['fileplandel']) {
							//Add to history
							$hist->add(
								FILEPLAN_POSITIONS_TABLE, $positions_array[$i]['ID'], 
								"DEL", 'fileplandel', _POSITION_REMOVED 
								. ': '.trim(str_replace('&emsp;', '', $positions_array[$i]['LABEL']))
								.' (' . $positions_array[$i]['ID'] . ')',
								$_SESSION['config']['databasetype'], 'fileplan'
							);
						}
					}
					
					//Reload and show message
					$js .= "destroyModal('modal_fileplan');";
					if ($origin == "admin") {
						//From admin
						$js .= "loadList('"
							.$_SESSION['config']['businessappurl']
							."index.php?page=fileplan_admin_managment&module=fileplan&"
							."fileplan_id=".$fileplan_id."&display=true".$parameters."');";
					} elseif ($origin == "manage") {
						//From manage
						$js .= "loadList('".$_SESSION['config']['businessappurl']
								."index.php?display=true&page=fileplan_managment"
								."&module=fileplan".$parameters."');";
					}
					$js .= "window.top.$('main_info').innerHTML = '"._POSITION_REMOVED.': '
						.$position_label."';";
				} else {
					$error = functions::wash_html($position_id.': '._POSITION_NOT_EXISTS.'!','NONE');
					$status = 1;
				}
			} else {
				$error = functions::wash_html(_POSITION_ID.' '._IS_EMPTY.'!','NONE');
				$status = 1;
			}
		} else {
            $error = functions::wash_html(_FILEPLAN_ID.' '._IS_EMPTY.'!','NONE');
            $status = 1;
        }
    break;
    case 'disPosition':
		if (isset($_REQUEST['fileplan_id']) && !empty($_REQUEST['fileplan_id'])) {
			//Get fileplan ID
			$fileplan_id = $_REQUEST['fileplan_id'];
			
			//Get position ID
			if (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {
				$position_id = $_REQUEST['id'];
				
				//Check if position ID exists
				if($fileplan->positionAlreadyExists($fileplan_id, $position_id)) {
	
					//Get array position
					$this_position_array = $fileplan->getPosition($fileplan_id, $position_id);
					//Get position tree
					$positions_array = $fileplan->getPositionsTree($fileplan_id, $positions_array, $this_position_array);
					//Init label
					$position_label = '';
					
					for($i=0; $i < count($positions_array); $i++) {
						//Only if enable
						if ($fileplan->isEnable($fileplan_id, $positions_array[$i]['ID'])) {
						
							$position_label .= $positions_array[$i]['LABEL'] .'<br/>';
							//Query
							$stmt = $db->query(
								"UPDATE ".FILEPLAN_POSITIONS_TABLE
								. " SET enabled = ? WHERE fileplan_id = ?"
								. " AND position_id = ?"
								. " AND enabled = ?"
								,array('N',$fileplan_id,$positions_array[$i]['ID'],'Y'));
								
							//History
							if ($_SESSION['history']['fileplanup']) {
								//Add to history
								$hist->add(
									FILEPLAN_POSITIONS_TABLE, $positions_array[$i]['ID'], "UP", 'fileplanup',
									_POSITION_DISABLED .': '.trim(str_replace('&emsp;', '', $positions_array[$i]['LABEL']))
									.' (' . $positions_array[$i]['ID'] . ')',
									$_SESSION['config']['databasetype'], 'fileplan'
								);
							}
						}
					}
	
					//Reload and show message
					if ($origin == "admin") {
						//From admin
						$js .= "loadList('"
							.$_SESSION['config']['businessappurl']
							."index.php?page=fileplan_admin_managment&module=fileplan&"
							."fileplan_id=".$fileplan_id."&display=true".$parameters."');";
					} elseif ($origin == "manage") {
						//From manage
						$js .= "loadList('".$_SESSION['config']['businessappurl']
								."index.php?display=true&page=fileplan_managment"
								."&module=fileplan".$parameters."');";
					}
					$js .= "window.top.$('main_info').innerHTML = '"._POSITION_DISABLED.': '
						.$position_label."';";
				} else {
					$error = functions::wash_html($position_id.': '._POSITION_NOT_EXISTS.'!','NONE');
					$status = 1;
				}
			} else {
				$error = functions::wash_html(_POSITION_ID.' '._IS_EMPTY.'!','NONE');
				$status = 1;
			}
		} else {
            $error = functions::wash_html(_FILEPLAN_ID.' '._IS_EMPTY.'!','NONE');
            $status = 1;
        }
		
    break;
    case 'enaPosition':
		if (isset($_REQUEST['fileplan_id']) && !empty($_REQUEST['fileplan_id'])) {
			//Get fileplan ID
			$fileplan_id = $_REQUEST['fileplan_id'];
			
			//Get position ID
			if (isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {
				$position_id = $_REQUEST['id'];
				
				//Check if position ID exists
				if($fileplan->positionAlreadyExists($fileplan_id, $position_id)) {
	
					//Get array position
					$this_position_array = $fileplan->getPosition($fileplan_id, $position_id);
					//Get position tree
					$positions_array = $fileplan->getPositionsTree($fileplan_id, $positions_array, $this_position_array);	
	
					//Get parents (enable parent when children is enable)
					$parents_array = array();
					$parents_array = $fileplan->getParents($parents_array, $fileplan_id, $position_id);
					//Merge all
					$positions_array = array_merge($positions_array, $parents_array);
					//Init label
					$position_label = '';
					
					for($i=0; $i < count($positions_array); $i++) {
						//Only if disable
						if ($fileplan->isEnable($fileplan_id, $positions_array[$i]['ID']) === false) {
							$position_label .= functions::wash_html($positions_array[$i]['LABEL']).'<br/>';
							//Query
							$stmt = $db->query(
								"UPDATE ".FILEPLAN_POSITIONS_TABLE
								. " SET enabled = ? WHERE fileplan_id = ?"
								. " AND position_id = ?"
								. " AND enabled = ?"
								,array('Y',$fileplan_id,$positions_array[$i]['ID'],'N'));
								
							//History
							if ($_SESSION['history']['fileplanup']) {
								//Add to history
								$hist->add(
									FILEPLAN_POSITIONS_TABLE, $positions_array[$i]['ID'], "UP", 'fileplanup',
									_POSITION_ENABLED .': '.functions::wash_html($positions_array[$i]['LABEL'])
									.' (' . $positions_array[$i]['ID'] . ')',
									$_SESSION['config']['databasetype'], 'fileplan'
								);
							}
						}
					}
					
					//Reload and show message
					if ($origin == "admin") {
						//From admin
						$js .= "loadList('"
							.$_SESSION['config']['businessappurl']
							."index.php?page=fileplan_admin_managment&module=fileplan&"
							."fileplan_id=".$fileplan_id."&display=true".$parameters."');";
					} elseif ($origin == "manage") {
						//From manage
						$js .= "loadList('".$_SESSION['config']['businessappurl']
								."index.php?display=true&page=fileplan_managment"
								."&module=fileplan".$parameters."');";
					}
					$js .= "window.top.$('main_info').innerHTML = '"._POSITION_ENABLED.': '
						.$position_label."';";
				} else {
					$error = functions::wash_html($position_id.': '._POSITION_NOT_EXISTS.'!','NONE');
					$status = 1;
				}
			} else {
				$error = functions::wash_html(_POSITION_ID.' '._IS_EMPTY.'!','NONE');
				$status = 1;
			}
		} else {
            $error = functions::wash_html(_FILEPLAN_ID.' '._IS_EMPTY.'!','NONE');
            $status = 1;
        }
    break;
    case 'setPosition':
		//Reset checked positions array
		unset($_SESSION['checked_positions']);
        $_SESSION['checked_positions'] = array();
		
		$fileplan_id ='';
        if (isset($_REQUEST['values']) && count($_REQUEST['values']) > 0) {
			//Selected ressources
			$selected_values = array();
			$selected_values = $_REQUEST['values'];
			
            //IF Collection
			if (isset($_REQUEST['coll_id'])) {
                $coll_id = $_REQUEST['coll_id'];
				$extraContent .= '<input type="hidden" name="coll_id" value="'.$coll_id.'" />';
				//Merge coll_id and res_id
				for($i = 0; $i < count($selected_values); $i++) {
					$selected_values[$i] = $coll_id.'@@'.$selected_values[$i];
				}
            }
            //Get selected res_id
            $values_array = array();
            //Filter empty and double
            $values_array = array_unique(array_filter($selected_values));
            $values = join(',', $values_array);
			//Add to url
			$extraUrl .="&res_id=".$values;
            // $content .='AVANT: '.join(',', $selected_values) .'<br/>APRES: '.$values ; //DEBUG
            
            //Selected label (update mode)
            if (isset($_REQUEST['actual_position_id']) && !empty($_REQUEST['actual_position_id'])) {
				if (isset($_REQUEST['fileplan_id']) && !empty($_REQUEST['fileplan_id'])) {
					//fileplan ID
					$fileplan_id = $_REQUEST['fileplan_id'];
					//Preselect actual position
					$_SESSION['checked_positions'][$fileplan_id][$_REQUEST['actual_position_id']] = 'true';
					//Add it to next url step...
					$extraUrl .="&actual_position_id=".$_REQUEST['actual_position_id'];
					//...and to hidden field
					$extraContent .=  '<input type="hidden" name="actual_position_id" value="'.$_REQUEST['actual_position_id'].'" />';
				} else {
					$error = functions::wash_html(_FILEPLAN_ID.' '._IS_EMPTY.'!','NONE');
					$status = 1;
				}
            }
            //Form
            $content .= '<form name="formFileplan" id="formFileplan" method="post" action="#" class="forms">';
            $content .= $extraContent;
            $content .= '<input type="hidden" name="res_id" value="'.$values.'" />';
			$content .= '<h2>'._SET_DOC_TO_POSITION.' : </h2>';
			$content .= '<label>'._FILEPLAN.': </label><br/>';
			$content .='<select name="fileplan_id" id="fileplan_id" class="fileplan_position" '
				.'onChange="loadFileplanList(\'selectedPosition\', \'positionsList\', \''
                .$_SESSION['config']['businessappurl'].'index.php?display=true&module='
                .'fileplan&page=positions_checked_list_autocompletion'.$extraUrl
				.'&fileplan_id=\' + document.formFileplan.fileplan_id.value);">';
			$content .='<option value="">'._CHOOSE_FILEPLAN.'</option>';
			//User personnal fileplan
			$userFileplanArray = $fileplan->getUserFileplan();
			//Entities fileplan
			$entitiesFileplanArray = array();
			if ($core_tools->test_service('put_doc_in_fileplan', 'fileplan', false)) {
				$entitiesFileplanArray = $fileplan->getEntitiesFileplan();
			}
			//Merge all
			$fileplan_array = array_merge ($userFileplanArray, $entitiesFileplanArray);
			for($i=0; $i < count($fileplan_array); $i++) {
				//Is enable ?
				if ($fileplan_array[$i]['ENABLED'] == 'Y') { 
					//Selected?
					($fileplan_id == $fileplan_array[$i]['ID'] || count($fileplan_array) == 1)? $selected = ' selected="selected"' : $selected = '';
					$content .='<option value="'.$fileplan_array[$i]['ID'].'"'.$selected.' >'
						.$fileplan_array[$i]['LABEL'].'</option>';
				}
			}
			
			$content .='</select><br/><br/>';
			//Autocompletion
			$content .= '<label>'._SEARCH.': </label><br/>';
            $content .= '<input type="text" name="selectedPosition"  id="selectedPosition"'
                .' value="" class="fileplan_position" onKeyUp="loadFileplanList(\'selectedPosition\', \'positionsList\', \''
                .$_SESSION['config']['businessappurl'].'index.php?display=true&module='
                .'fileplan&page=positions_checked_list_autocompletion'.$extraUrl
				.'&fileplan_id=\' + document.formFileplan.fileplan_id.value);" /><br/><br/>';
			//Content 
            $content .= '<div style="width:585px; height:300px; border:1px solid; overflow-x:hidden; overflow-y:auto; padding:5px;">'; 
            $content .= '<div id="loadingFileplan" style="display:none;"><i class="fa fa-spinner fa-2x"></i></div>';
            $content .= '<div id="positionsList"></div>';
            $content .='</div>';
            
            //Buttons
            $content .='<hr />';
            $content .='<div align="center">';
            $content .=' <input type="button" name="valid" value="&nbsp;'._VALIDATE
                .'&nbsp;" id="valid" class="button" onclick="validFileplanForm(\''
                .$path_to_script.'&mode=set&origin='.$origin.'\', \'formFileplan\');" />&nbsp;';
            $content .='<input type="button" name="cancel" id="cancel" class="button"  value="'
                ._CANCEL.'" onclick="destroyModal(\'modal_fileplan\');"/>';
            $content .='</div">';
            $content .= '</form>';
            $content .='<script type="text/javascript">loadFileplanList(\'selectedPosition\', \'positionsList\', \''
                .$_SESSION['config']['businessappurl'].'index.php?display=true&module='
                .'fileplan&page=positions_checked_list_autocompletion'.$extraUrl
				.'&fileplan_id=\' + document.formFileplan.fileplan_id.value);</script>';
        } else {
            $error = functions::wash_html(_CHOOSE_ONE_DOC.'!','NONE');
            $status = 1;
        }
    break;
    case 'set':
         //Get RES_ID
        if (isset($_REQUEST['res_id']) && !empty($_REQUEST['res_id'])) {

			//Build res_array
			$res_array = $fileplan->buildResArray($_REQUEST['res_id']);
			
		    //Fileplan ID
			if (isset($_REQUEST['fileplan_id']) && !empty($_REQUEST['fileplan_id'])) {
				
				$fileplan_id = $_REQUEST['fileplan_id'];
				//Selected position (update mode)
				$actual_position_id = '';
				
				if(!$_REQUEST['position']){
				
					foreach ($_SESSION['origin_positions'] as $key => $value) {
						$fileplan->remove($fileplan_id, $value, $res_array);
					}
				}else{
					if(!$_SESSION['origin_positions']){
							$_SESSION['origin_positions']=array();
					}
					$fileplan_diff=array_diff($_SESSION['origin_positions'],$_REQUEST['position']);

					foreach ($fileplan_diff as $key => $value) {
						$fileplan->remove($fileplan_id, $value, $res_array);
					}
				}
				
				//if (isset($_REQUEST['actual_position_id']) && !empty($_REQUEST['actual_position_id'])) {
				//	$actual_position_id = $_REQUEST['actual_position_id'];
					//Remove Actual position if not selected
					/*if (
						!isset($_REQUEST['position']) 
						|| (count($_REQUEST['position']) == 0)
						|| !in_array($actual_position_id, $_REQUEST['position'])
						) {*/
						//$fileplan->remove($fileplan_id, '14', $res_array);
					//}
				//}
				//Check POSITIONS
					//I	 : Mode SET, position required
					//II : Mode DELETE, remove the actual position from document(s)
					//III: Mode MOVE, move document(s) in another position
                /*if (
                    (isset($_REQUEST['position']) && count($_REQUEST['position']) > 0 && empty($actual_position_id)) 		//I
                    || (!isset($_REQUEST['position']) && count($_REQUEST['position']) == 0 && !empty($actual_position_id)) 	//II
                    || (isset($_REQUEST['position']) && count($_REQUEST['position']) > 0 && !empty($actual_position_id)) 	//III
                    ) 
				{*/
					//DEBUG
					// print_r($_REQUEST['position']);
					// print_r($res_array);
					// exit;
					
					//Process!
                    for($i = 0; $i < count($_REQUEST['position']); $i++) {
					
                        //Position to treat
                        $position_id = $_REQUEST['position'][$i];
						
						//Check if position exists
						if($fileplan->positionAlreadyExists($fileplan_id, $position_id)) {
                            //For each res_id
                            for($j = 0; $j < count($res_array); $j++) {
                            
                                $res_id  = $res_array[$j]['RES_ID'];
                                $coll_id = $res_array[$j]['COLL_ID'];
								
                                //Set position
                                $fileplan->set($fileplan_id, $position_id, $res_id, $coll_id);
								
								//Unset checked positions array
								unset($_SESSION['checked_positions']);
                            }
                        } else {
                            $error = functions::wash_html($position_id.': '._POSITION_NOT_EXISTS.'!','NONE');
                            $status = 1;
                            break;
                        }
						
						//History
						if ($_SESSION['history']['fileplanup']) {
							//Add to history
							$hist->add(
								FILEPLAN_RES_POSITIONS_TABLE, $fileplan_id, "UP", 'fileplanup',
								_DOC_ADDED_TO_POSITION .': '.$position_id
								.' (' . $coll_id.'/'.$res_id . ')',
								$_SESSION['config']['databasetype'], 'fileplan'
							);
						}
                    }
                    
                    //Reload list and show message
					$js .= "destroyModal('modal_fileplan');unCheckAll();";
					$js .= $list_origin;
					$js .= "window.top.$('main_info').innerHTML = '"._DOC_ADDED_TO_POSITION."';main_info.style.display = 'table-cell';Element.hide.delay(10, 'main_info');";

                /*} else {
                    $error = functions::wash_html(_CHOOSE_ONE_POSITION.'!','NONE');
                    $status = 1;
                }*/
				
            } else {
				$error = functions::wash_html(_CHOOSE_FILEPLAN.'!','NONE');
				$status = 1;
			}
        } else {
            $error = functions::wash_html(_CHOOSE_ONE_DOC.'!','NONE');
            $status = 1;
        }
    break;
    case 'remove':
		if (isset($_REQUEST['fileplan_id']) && !empty($_REQUEST['fileplan_id'])) {
			//Get fileplan ID
			$fileplan_id = $_REQUEST['fileplan_id'];
			
			if (isset($_REQUEST['actual_position_id']) && !empty($_REQUEST['actual_position_id'])) {
				//Get POSITION
				$position_id = $_REQUEST['actual_position_id'];
				
				if (isset($_REQUEST['res_id']) && !empty($_REQUEST['res_id'])) {
					//Get RES_ID
					$res_id = $_REQUEST['res_id'];
					
					if (isset($_REQUEST['coll_id']) && !empty($_REQUEST['coll_id'])) {
						//Get COLL_ID
						$coll_id = $_REQUEST['coll_id'];

						//Build res_array
						$res_array = $fileplan->buildResArray($coll_id.'@@'.$res_id);
		
						//Remove doc from position
						$fileplan->remove($fileplan_id, $position_id, $res_array);
						
						//History
						if ($_SESSION['history']['fileplandel']) {
							//Add to history
							$hist->add(
								FILEPLAN_RES_POSITIONS_TABLE, $fileplan_id, "UP", 'fileplandel',
								_DOC_REMOVED_FROM_POSITION .': '.$position_id
								.' (' . $coll_id.'/'.$res_id . ')',
								$_SESSION['config']['databasetype'], 'fileplan'
							);
						}
					} else {
						$_SESSION['error'] = _COLLECTION." "._IS_EMPTY;
						$js = "window.top.location.href='".$_SESSION['config']['businessappurl']."index.php?reinit=true';";
					}
					//Reload list and show message
					$js .= $list_origin;
					$js .= "window.top.$('main_info').innerHTML = '"._DOC_REMOVED_FROM_POSITION."';";
					
				} else {
					$error = functions::wash_html(_DOC.' '._IS_EMPTY.'!','NONE');
					$status = 1;
				}
			} else {
				$error = functions::wash_html(_POSITION_ID.' '._IS_EMPTY.'!','NONE');
				$status = 1;
			}
		} else {
            $error = functions::wash_html(_FILEPLAN_ID.' '._IS_EMPTY.'!','NONE');
            $status = 1;
        }
    break;
    case 'checkPosition':
		//Checked value
        if (isset($_REQUEST['checked']) && isset($_REQUEST['value'])) {
			//Fileplan ID
			if (isset($_REQUEST['fileplan_id']) && !empty($_REQUEST['fileplan_id'])) {
				//
				$fileplan_id = $_REQUEST['fileplan_id'];
				
				if ($_REQUEST['checked'] == 'true') {
					$_SESSION['checked_positions'][$fileplan_id][$_REQUEST['value']] = $_REQUEST['checked'];
				} else  if ($_REQUEST['checked'] == 'false') {
					unset($_SESSION['checked_positions'][$fileplan_id][$_REQUEST['value']]);
				}
				/*$js = 'loadFileplanList(\'selectedPosition\', \'positionsList\', \''
					.$_SESSION['config']['businessappurl'].'index.php?display=true&module='
					.'fileplan&page=positions_checked_list_autocompletion'.$extraUrl
					.'&fileplan_id='.$_REQUEST['fileplan_id'].'\');';*/
			} else {
				$error = functions::wash_html(_FILEPLAN_ID.' '._IS_EMPTY.'!','NONE');
				$status = 1;
			}
        } else {
            $error = functions::wash_html(_UNKNOW_ERROR.'!','NONE');
            $status = 1;
        }
    break;
}

echo "{status : " . $status . ", content : '" . addslashes($debug.$content) . "', error : '" . addslashes($error) . "', exec_js : '".addslashes($js)."'}";
exit ();
?>

