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
* @brief    Positions list of the current fileplan (administrator mode)
*
* @file     fileplan_admin_managment.php
* @author   Yves Christian Kpakpo <dev@maarch.org>
* @date     $date$
* @version  $Revision$
* @ingroup  fileplan
*/

require_once "core" . DIRECTORY_SEPARATOR . "class" . DIRECTORY_SEPARATOR 
	. "class_request.php";
require_once "apps" . DIRECTORY_SEPARATOR . $_SESSION['config']['app_id']
	.DIRECTORY_SEPARATOR ."class" . DIRECTORY_SEPARATOR . "class_lists.php";
require_once "modules" . DIRECTORY_SEPARATOR . "fileplan" . DIRECTORY_SEPARATOR
    . "class" . DIRECTORY_SEPARATOR . "class_modules_tools.php";
    
$core_tools = new core_tools();
$list       = new lists();   
$fileplan   = new fileplan();
$request    = new request();

if (isset($_REQUEST['fileplan_id']) && !empty($_REQUEST['fileplan_id'])) {
	//Get fileplan ID
	$fileplan_id = $_REQUEST['fileplan_id'];
	//Get fileplan info
	$fileplanArray = $fileplan->getFileplan($fileplan_id, false);
	$fileplan_id	= $fileplanArray['ID'];
	$fileplan_label	= $fileplanArray['LABEL'];
}
	
if (!empty($fileplan_id) && $fileplan->isPersonnalFileplan($fileplan_id) === false) {
	//
	$pathArray = array();

	if (isset($_REQUEST['load'])) {
	
		$core_tools->test_service('admin_fileplan', 'fileplan');

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
		$page_path = $_SESSION['config']['businessappurl'].'index.php?page=fileplan_admin_managment&module=fileplan&load';
		$page_label = _MANAGE_FILEPLAN_SHORT;
		$page_id = "fileplan_admin_managment";
		$core_tools->manage_location_bar($page_path, $page_label, $page_id, $init, $level);
		/***********************************************************/
		?>
		<h1><i class="fa fa-files-o fa-2x" title="" /></i>
			<?php echo _MANAGE_FILEPLAN;?></h1>
		<div id="inner_content">
			<div class="block">
				<h2>
					<a href="<?php echo $_SESSION['config']['businessappurl'];
					?>index.php?page=fileplan_admin_positions&module=fileplan&fileplan_id=<?php
					functions::xecho($fileplan_id);?>" class="back"><?php 
					echo _VIEW_FILEPLAN;?></a>&nbsp;/&nbsp;
					<span class="selected_link"><?php echo _MANAGE_FILEPLAN;?></span>            
				</h2>&nbsp;
			<?php
			$parameters = '';
			if (isset($_REQUEST['order']) && !empty($_REQUEST['order'])) $parameters .= '&order='.$_REQUEST['order'];
			if (isset($_REQUEST['order_field']) && !empty($_REQUEST['order_field'])) $parameters .= '&order_field='.$_REQUEST['order_field'];
			if (isset($_REQUEST['what']) && !empty($_REQUEST['what'])) $parameters .= '&what='.$_REQUEST['what'];
			if (isset($_REQUEST['start']) && !empty($_REQUEST['start'])) $parameters .= '&start='.$_REQUEST['start'];

			//Load list
			$target = $_SESSION['config']['businessappurl']
				.'index.php?page=fileplan_admin_managment'
				.'&module=fileplan&fileplan_id='.$fileplan_id
				.$parameters;
			$listContent = $list->loadList($target, true, 'divList', 'false');
			echo $listContent;
			
			//Reset error
			$_SESSION['error'] = "";
			?>
		</div>
	<?php
	} else {
		//Table
		$table = FILEPLAN_VIEW;
		$select[$table]= array(); 
		
	//Fields
		array_push($select[$table], 
				"position_id", 
				"fileplan_id",
				"fileplan_label",
				"enabled", 
				"user_id", 
				"position_label", 
				"parent_id", 
				"position_id as position_path",
				"position_enabled",
				"count_document");
	//Where clause
		$where_tab = array();
		$array_what = array();
	
		$where_tab[] = "(fileplan_id  = ?)";
		$array_what[] = $fileplan_id;
		//Filtre alphabetique et champ de recherche
		$what = $list->getWhatSearch();
		if (!empty($what)) {
			$where_tab[] = "(lower(position_label) like lower(?))";
			$array_what[] = $what.'%';

		}
		//Build where
		$where = implode(' and ', $where_tab);
		// if (!empty($where)) $where = ' where '.$where;
		
	//Order
		$order = $order_field = '';
		$order = $list->getOrder();
		$order_field = $list->getOrderField();
		if (!empty($order_field) && !empty($order)) 
			$orderstr = "order by ".$order_field." ".$order;
		else  {
			$list->setOrder('asc');
			$list->setOrderField('position_path');
			$orderstr = "order by position_path asc";
		}

	//get start
			$start = $list->getStart();
			
	//URL extra Parameters  
		$parameters = '';
		$start = $list->getStart();
		if (!empty($order_field) && !empty($order)) $parameters .= '&order='.$order.'&order_field='.$order_field;
		if (!empty($what)) $parameters .= '&what='.$what;
		if (!empty($start)) $parameters .= '&start='.$start;
			
	//Request
		$tab=$request->PDOselect($select,$where,$array_what,$orderstr,$_SESSION['config']['databasetype']);
		// $request->show();
		
	//Result array    
		for ($i=0;$i<count($tab);$i++)
		{
			for ($j=0;$j<count($tab[$i]);$j++)
			{
				foreach(array_keys($tab[$i][$j]) as $value)
				{
					if($tab[$i][$j][$value]=="position_id")
					{
						$id = $tab[$i][$j]['value'];
						$tab[$i][$j]["position_id"]=$tab[$i][$j]['value'];
						$tab[$i][$j]["label"]= _ID;
						$tab[$i][$j]["size"]="5";
						$tab[$i][$j]["label_align"]="left";
						$tab[$i][$j]["align"]="left";
						$tab[$i][$j]["valign"]="bottom";
						$tab[$i][$j]["show"]=true;
						$tab[$i][$j]["order"]= "position_id";
					}

					if($tab[$i][$j][$value]=="position_label")
					{
						$tab[$i][$j]['value']=$request->show_string($tab[$i][$j]['value']);
						$tab[$i][$j]["label"]=_POSITION_NAME;
						$tab[$i][$j]["size"]="25";
						$tab[$i][$j]["label_align"]="left";
						$tab[$i][$j]["align"]="left";
						$tab[$i][$j]["valign"]="bottom";
						$tab[$i][$j]["show"]=true;
						$tab[$i][$j]["order"]= "position_label";
					}
					if($tab[$i][$j][$value]=="parent_id")
					{
						$tab[$i][$j]['value']= $fileplan->getPosition($fileplan_id, $tab[$i][$j]['value'], 'position_label');
						$tab[$i][$j]["label"]=_POSITION_PARENT;
						$tab[$i][$j]["size"]="25";
						$tab[$i][$j]["label_align"]="left";
						$tab[$i][$j]["align"]="left";
						$tab[$i][$j]["valign"]="bottom";
						$tab[$i][$j]["show"]=true;
						$tab[$i][$j]["order"]= "parent_position_id";
					}
					if($tab[$i][$j][$value]=="position_path")
					{
						$tab[$i][$j]['value']=$fileplan->getPositionPath($fileplan_id, $tab[$i][$j]['value']);
						$tab[$i][$j]["label"]=_POSITION_PATH;
						$tab[$i][$j]["size"]="50";
						$tab[$i][$j]["label_align"]="left";
						$tab[$i][$j]["align"]="left";
						$tab[$i][$j]["valign"]="bottom";
						$tab[$i][$j]["show"]=true;
						$tab[$i][$j]["order"]= "position_path";
					}

					if($tab[$i][$j][$value]=="position_enabled")
					{
						$tab[$i][$j]["label"]=_ENABLED;
						$tab[$i][$j]["size"]="1";
						$tab[$i][$j]["label_align"]="left";
						$tab[$i][$j]["align"]="center";
						$tab[$i][$j]["valign"]="bottom";
						$tab[$i][$j]["show"]=false;
						$tab[$i][$j]["order"]='position_enabled';
					}
					
					if($tab[$i][$j][$value]=="fileplan_id")
					{
						$tab[$i][$j]["label"]=_FILEPLAN_ID;
						$tab[$i][$j]["size"]="5";
						$tab[$i][$j]["label_align"]="left";
						$tab[$i][$j]["align"]="center";
						$tab[$i][$j]["valign"]="bottom";
						$tab[$i][$j]["show"]=false;
						$tab[$i][$j]["order"]='fileplan_id';
					}
				}
			}
		}

		//List parameters
		$paramsTab = array();
		$paramsTab['bool_modeReturn'] = false;                                              //Desactivation du mode return (vs echo)
		$paramsTab['bool_bigPageTitle'] = false;
		$paramsTab['pageTitle'] =  _FILEPLAN_SHORT." : "
			.count($tab).' '._FILEPLAN_POSITIONS;              								//Titre de la page
		// $paramsTab['pagePicto'] = $_SESSION['config']['businessappurl']
			// ."static.php?module=fileplan&filename=manage_fileplan_b.gif";               	//Image (pictogramme) de la page
		$paramsTab['urlParameters'] = 'fileplan_id='.$fileplan_id;                          //Parametres d'url supplementaires
		$paramsTab['bool_sortColumn'] = true;                                               //Affichage Tri
		$paramsTab['bool_showSearchTools'] = true;                                          //Affiche le filtre alphabetique et le champ de recherche
		$paramsTab['searchBoxAutoCompletionUrl'] = $_SESSION['config']['businessappurl']
			."index.php?display=true&module=fileplan&page="
			."positions_list_autocompletion&fileplan_id=".$fileplan_id;   					//Script pour l'autocompletion
		$paramsTab['searchBoxAutoCompletionMinChars'] = 2;                                  //Nombre minimum de caractere pour activer l'autocompletion (1 par defaut)
		$paramsTab['linesToShow'] = 15;                                                     //Nombre de lignes a afficher (parametre de config.xml par defaut)
		$paramsTab['bool_showAddButton'] = true;                                            //Affichage du bouton Nouveau
		$paramsTab['addButtonLabel'] = _NEW_POSITION;                                       //Libellé du bouton Nouveau
		$paramsTab['addButtonScript'] = "showFileplanForm('"
			.$_SESSION['config']['businessappurl']  
			. "index.php?display=true&module=fileplan&page=fileplan_ajax_script"
			. "&origin=admin&&mode=addPosition&fileplan_id="
			. $fileplan_id.$parameters."')";						//Action sur le bouton nouveau

		//Action icons array
		$paramsTab['actionIcons'] = array();
			$disable = array(
					"type" => "switch", 
					"on" => array(
							   "script"             =>  "execFileplanScript('".$_SESSION['config']['businessappurl']
														. "index.php?display=true&module=fileplan&page=fileplan_ajax_script"
														. "&origin=admin&mode=enaPosition&id=@@position_id@@&fileplan_id=@@fileplan_id@@"
														. $parameters."')",
								"class"             =>  'authorize',
								"tooltip"           =>  _ENABLE_POSITION,
								"label"             =>  _ENABLE_POSITION
								),            
					"off" => array(
							   "script"             =>  "execFileplanScript('".$_SESSION['config']['businessappurl']
														. "index.php?display=true&module=fileplan&page=fileplan_ajax_script"
														. "&origin=admin&mode=disPosition&id=@@position_id@@&fileplan_id=@@fileplan_id@@"
														. $parameters."')",
								"class"             =>  'suspend',
								"tooltip"           =>  _DISABLE_POSITION,
								"label"             =>  _DISABLE_POSITION
								),
					"switchRules" => "@@position_enabled@@ == 'N'"
					);
					
			$update = array(
					"script"        => "showFileplanForm('".$_SESSION['config']['businessappurl']
											. "index.php?display=true&module=fileplan&page=fileplan_ajax_script"
											. "&origin=admin&mode=upPosition&id=@@position_id@@&fileplan_id=@@fileplan_id@@"
											. $parameters."')",
					"class"         =>  'change',
					"label"         =>  _EDIT_POSITION_SHORT,
					"tooltip"       =>  _EDIT_POSITION_SHORT,
					"disabledRules" =>  "@@position_enabled@@ == 'N'"
					);
					
			$delete = array(
					"script"        => "showFileplanForm('".$_SESSION['config']['businessappurl']
											. "index.php?display=true&module=fileplan&page=fileplan_ajax_script"
											. "&origin=admin&mode=delPosition&id=@@position_id@@&fileplan_id=@@fileplan_id@@"
											. $parameters."', '', '500px', '400px')",
					"class"         =>  'delete',
					"label"         =>  _DELETE_POSITION,
					"tooltip"       =>  _DELETE_POSITION
					);
			array_push($paramsTab['actionIcons'], $disable);          
			array_push($paramsTab['actionIcons'], $update);          
			array_push($paramsTab['actionIcons'], $delete);
		
		//Afficher la liste
		echo '<br/>';
		$list->showList($tab, $paramsTab, 'position_id');
	}
	echo '</div>';
} else {
	echo '<script type="text/javascript">window.top.location.href=\'' 
			. $_SESSION['config']['businessappurl']
			. 'index.php?page=fileplan_admin&module=fileplan&load\';</script>';
	exit;
} 
