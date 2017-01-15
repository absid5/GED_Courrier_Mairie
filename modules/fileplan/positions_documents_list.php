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
* @brief    Displays documents list in fileplan tree
*
* @file     positions_documents_list.php
* @author   Yves Christian Kpakpo <dev@maarch.org>
* @date     $date$
* @version  $Revision$
* @ingroup  fileplan
*/

require_once "core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php";
require_once "core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_security.php";
require_once "core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_manage_status.php";
require_once "apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR
            ."class".DIRECTORY_SEPARATOR."class_lists.php";
require_once "modules" . DIRECTORY_SEPARATOR . "fileplan" . DIRECTORY_SEPARATOR
    . "class" . DIRECTORY_SEPARATOR
    . "class_modules_tools.php";
         
$security   = new security();
$core_tools = new core_tools();
$db    		= new Database();
$status_obj = new manage_status();
$list       = new lists();
$fileplan   = new fileplan();

$status = 0;
$change_fileplan = false;
	
if(isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {

	$branch_array = array();
	$branch_array = explode('@@', $_REQUEST['id']);
	
	//Get  ID
	$fileplan_id = $branch_array[0];
	$position_id = $branch_array[1];
	
	//Process
	if(!empty($fileplan_id) && !empty($position_id)) {
		
		//Change fileplan
		
		$change_fileplan = $fileplan->userCanChangeFileplan($fileplan_id);
		
		//URL extra Parameters  
		$parameters = '&id='.$_REQUEST['id'];
		$start = $list->getStart();
		if (!empty($order_field) && !empty($order)) $parameters .= '&order='.$order.'&order_field='.$order_field;
		if (!empty($start)) $parameters .= '&start='.$start;
		
		//Order
		$order = $order_field = '';
		$order = $list->getOrder();
		$order_field = $list->getOrderField();
		if (!empty($order_field) && !empty($order)) 
			$orderstr = "order by ".$order_field." ".$order;
		else  {
			$list->setOrder();
			$list->setOrderField('coll_id');
			$orderstr = "order by coll_id desc";
		}
		//Query
		$stmt = $db->query(
					"SELECT * FROM "
                    . FILEPLAN_RES_POSITIONS_TABLE
                    . " WHERE fileplan_id = ?"
                    . " AND position_id = ?"
                    . " ".$orderstr
		,array($fileplan_id,$position_id));

		
		$description = $fileplan->getPositionPath($fileplan_id, $position_id, true);
		
		$resId_array = array();
		//
		if($stmt->rowCount() > 0) {
		
			//Build list array
			$tab=array();
			while($line = $stmt->fetchObject())
			{
				//Get view fort ressource collection
				$view = $security->retrieve_view_from_coll_id($line->coll_id);
				
				//Query 
				$stmt2 = $db->query(
					"SELECT res_id, alt_identifier, res_id as right_doc, status, type_label,"
					." category_id, subject, creation_date FROM "
                    . $view . " WHERE res_id = ?"
				,array($line->res_id));

				$res = $stmt2->fetch(PDO::FETCH_ASSOC);
				$temp= array();
				//Create list ID
				array_push($temp, array('column'=>'list_id', 'value'=>$line->coll_id.'@@'.$line->res_id));
				//Keep collection ID & LABEL
				array_push($temp, array('column'=>'coll_id', 'value'=>$line->coll_id));
				array_push($temp, array('column'=>'coll_label', 'value'=>$security->get_script_from_coll($line->coll_id, "label")));
				
				//Get the details page (from colletion)
				$coll_script_details = $security->get_script_from_coll($line->coll_id, "script_details");
				$coll_script_details = substr($coll_script_details,0, strlen($coll_script_details) - 4);
				array_push($temp, array('column'=>'page_details', 'value'=>$coll_script_details));
				
				//Get the columns
				foreach (array_keys($res) as $resval) {
					if (!is_int($resval)) {
						array_push($temp,array('column'=>$resval,'value'=>$res[$resval]));
					}
				}
				//put in array
				array_push($tab, $temp);
			}

		
			//Result Array
				for ($i=0;$i<count($tab);$i++)
				{
					for ($j=0;$j<count($tab[$i]);$j++)
					{
						foreach(array_keys($tab[$i][$j]) as $value)
						{
							if($tab[$i][$j][$value]=="list_id")
							{
								$tab[$i][$j]["list_id"]=$tab[$i][$j]['value'];
								$tab[$i][$j]["label"]=_ID;
								$tab[$i][$j]["size"]="1";
								$tab[$i][$j]["label_align"]="left";
								$tab[$i][$j]["align"]="left";
								$tab[$i][$j]["valign"]="bottom";
								$tab[$i][$j]["show"]=false;
								$tab[$i][$j]["order"]=false;
							}
							if($tab[$i][$j][$value]=="res_id")
							{
								$display = false;
								$tab[$i][$j]["label"]=_GED_NUM;
								$tab[$i][$j]["size"]="1";
								$tab[$i][$j]["label_align"]="left";
								$tab[$i][$j]["align"]="left";
								$tab[$i][$j]["valign"]="bottom";
								if(_ID_TO_DISPLAY == 'res_id'){
									$display = true;
								}
								$tab[$i][$j]["show"]=$display;
								$tab[$i][$j]["order"]='res_id';
							}
							if($tab[$i][$j][$value]=="alt_identifier")
							{
								$display = false;
								$tab[$i][$j]["label"]=_CHRONO_NUMBER;
								$tab[$i][$j]["size"]="1";
								$tab[$i][$j]["label_align"]="left";
								$tab[$i][$j]["align"]="left";
								$tab[$i][$j]["valign"]="bottom";
								if(_ID_TO_DISPLAY == 'chrono_number'){
									$display = true;
								}
								$tab[$i][$j]["show"]=$display;
								$tab[$i][$j]["order"]='alt_identier';
							}
							if($tab[$i][$j][$value]=="coll_id")
							{
								$coll_id = $tab[$i][$j]['value']; //Keep collection ID
								$tab[$i][$j]["label"]=_COLLECTION;
								$tab[$i][$j]["size"]="1";
								$tab[$i][$j]["label_align"]="left";
								$tab[$i][$j]["align"]="left";
								$tab[$i][$j]["valign"]="bottom";
								$tab[$i][$j]["show"]=false;
								$tab[$i][$j]["order"]='coll_id';
							}		
							if($tab[$i][$j][$value]=="coll_label")
							{
								$tab[$i][$j]["label"]=_COLLECTION;
								$tab[$i][$j]["size"]="1";
								$tab[$i][$j]["label_align"]="left";
								$tab[$i][$j]["align"]="left";
								$tab[$i][$j]["valign"]="bottom";
								$tab[$i][$j]["show"]=false;
								$tab[$i][$j]["order"]='coll_label';
							}							
							if($tab[$i][$j][$value]=="page_details")
							{								
								$tab[$i][$j]["label"]=_DETAILS;
								$tab[$i][$j]["size"]="1";
								$tab[$i][$j]["label_align"]="left";
								$tab[$i][$j]["align"]="left";
								$tab[$i][$j]["valign"]="bottom";
								$tab[$i][$j]["show"]=false;
								$tab[$i][$j]["order"]=false;
							}
							if($tab[$i][$j][$value]=="right_doc")
							{
								$tab[$i][$j]['value']=($security->test_right_doc($coll_id, $tab[$i][$j]['value']) === true)? "true": "false";
								$tab[$i][$j]["label"]=_RIGHT;
								$tab[$i][$j]["size"]="1";
								$tab[$i][$j]["label_align"]="left";
								$tab[$i][$j]["align"]="left";
								$tab[$i][$j]["valign"]="bottom";
								$tab[$i][$j]["show"]=false;
								$tab[$i][$j]["order"]=false;
							}
							
							if($tab[$i][$j][$value]=="status")
							{
								$res_status = $status_obj->get_status_data($tab[$i][$j]['value'],$extension_icon);
								$statusCmp = $tab[$i][$j]['value'];
								$tab[$i][$j]['value'] = "<img src = '".$res_status['IMG_SRC']."' alt = '".$res_status['LABEL']."' title = '".$res_status['LABEL']."'>";
								$tab[$i][$j]["label"]=_STATUS;
								$tab[$i][$j]["size"]="4";
								$tab[$i][$j]["label_align"]="left";
								$tab[$i][$j]["align"]="left";
								$tab[$i][$j]["valign"]="bottom";
								$tab[$i][$j]["show"]=true;
								$tab[$i][$j]["order"]=true;
							}             
							if($tab[$i][$j][$value]=="subject")
							{
								$tab[$i][$j]["value"] = functions::cut_string(functions::show_string($tab[$i][$j]["value"]), 250);
								$tab[$i][$j]["label"]=_SUBJECT;
								$tab[$i][$j]["size"]="12";
								$tab[$i][$j]["label_align"]="left";
								$tab[$i][$j]["align"]="left";
								$tab[$i][$j]["valign"]="bottom";
								$tab[$i][$j]["show"]=true;
								$tab[$i][$j]["order"]=true;
							}
							if($tab[$i][$j][$value]=="category_id")
							{
								$_SESSION['mlb_search_current_category_id'] = $tab[$i][$j]["value"];
								$tab[$i][$j]["value"] = $_SESSION['mail_categories'][$tab[$i][$j]["value"]];
								$tab[$i][$j]["label"]=_CATEGORY;
								$tab[$i][$j]["size"]="10";
								$tab[$i][$j]["label_align"]="left";
								$tab[$i][$j]["align"]="left";
								$tab[$i][$j]["valign"]="bottom";
								$tab[$i][$j]["show"]=false;
								$tab[$i][$j]["order"]=false;
							}
							if($tab[$i][$j][$value]=="type_label")
							{
								$tab[$i][$j]["value"] = functions::show_string($tab[$i][$j]["value"]);
								$tab[$i][$j]["label"]=_TYPE;
								$tab[$i][$j]["size"]="10";
								$tab[$i][$j]["label_align"]="left";
								$tab[$i][$j]["align"]="left";
								$tab[$i][$j]["valign"]="bottom";
								$tab[$i][$j]["show"]=true;
								$tab[$i][$j]["order"]=true;
							}
							if($tab[$i][$j][$value]=="creation_date")
							{
								$tab[$i][$j]["value"]=$core_tools->format_date_db($tab[$i][$j]["value"], false);
								$tab[$i][$j]["label"]=_CREATION_DATE;
								$tab[$i][$j]["size"]="10";
								$tab[$i][$j]["label_align"]="left";
								$tab[$i][$j]["align"]="left";
								$tab[$i][$j]["valign"]="bottom";
								$tab[$i][$j]["show"]=false;
								$tab[$i][$j]["order"]=false;
							}
						}
					}
				}
				
			//List
				$listKey = 'list_id';                                                               //Clé de la liste
				$paramsTab = array();                                                               //Initialiser le tableau de paramètres
				$paramsTab['bool_sortColumn'] = true;                                               //Affichage Tri
				$paramsTab['pageTitle'] = '<h2 style="margin-left:0px;">'.$description.':</h2><br/> '
					.count($tab).' '._FOUND_DOC.'<br/>';     										//Titre de la page
				$paramsTab['bool_bigPageTitle'] = false;                                            //Affichage du titre en grand
				// $paramsTab['bool_showIconDocument'] = true;                                         //Affichage de l'icone du document
				// $paramsTab['bool_showIconDetails'] = true;                                          //Affichage de l'icone de la page de details
				$paramsTab['urlParameters'] = 'id='.$_REQUEST['id'].'&display=true';                //Parametres d'url supplementaires
				$paramsTab['listHeight'] = '380px';                                                 //Hauteur de la liste
				$paramsTab['linesToShow'] = 10;                                                     //Nombre de ligne a afficher
				$paramsTab['bool_changeLinesToShow'] = false;                                       //Modifier le nombre de ligne a afficher
				$paramsTab['listCss'] = 'listingsmall';                                             //CSS
				$paramsTab['divListId'] = 'list_doc';                                               //Id du Div de retour ajax
				$paramsTab['bool_checkBox'] = true;                                                 //Case a cocher
				$paramsTab['bool_standaloneForm'] = true;                                           //Formulaire
				$paramsTab['disabledRules'] = "@@right_doc@@ == 'false' || "
					.(int)$change_fileplan." == 0";                           						//Veroullage de ligne(heckbox ou radio button)
			   
				$paramsTab['tools'] = array();                                                      //Icones dans la barre d'outils
				$positions = array(
                    "script"        =>  "showFileplanList('".$_SESSION['config']['businessappurl']  
                                            . "index.php?display=true&module=fileplan&page=fileplan_ajax_script"
                                            . "&mode=setPosition&origin=fileplan&fileplan_id=".$fileplan_id
											. "&actual_position_id=". $position_id.$parameters
											. "', 'formList', '600px', '510px', '"
                                            . _CHOOSE_ONE_DOC."')",
                    "icon"          =>  'bookmark',
                    "tooltip"       =>  _FILEPLAN,
                    "disabledRules" =>  count($tab)." == 0 || ".(int)$change_fileplan." == 0"
                );  
                array_push($paramsTab['tools'],$positions);

				//Action icons array
				$paramsTab['actionIcons'] = array();
				$remove = array(
						"script"        => "execFileplanScript('".$_SESSION['config']['businessappurl']
												. "index.php?display=true&module=fileplan&page=fileplan_ajax_script"
												. "&mode=remove&origin=fileplan&fileplan_id=".$fileplan_id
												. "&actual_position_id=". $position_id."&res_id=@@res_id@@"
												. "&coll_id=@@coll_id@@".$parameters."')",
						"icon"          => "trash-o",
						"tooltip"       => _REMOVED_DOC_FROM_POSITION,
						"alertText"     =>  _REALLY_REMOVE_DOC_FROM_POSITION.": ".$description."?", 
						//"disabledRules" => (int)$change_fileplan." == 0"
						//"disabledRules" => "@@right_doc@@ == 'false'"
						);
				array_push($paramsTab['actionIcons'], $remove);
				$viewDoc = array(
						"script"    => "window.top.location='".$_SESSION['config']['businessappurl']
										."index.php?page=@@page_details@@&dir=indexing_searching&coll_id=@@coll_id@@&id=@@res_id@@'",
						"icon"      => "info",
						"tooltip"   => _DETAILS,
						"disabledRules" => "@@right_doc@@ == 'false'"
						);
				array_push($paramsTab['actionIcons'], $viewDoc);
		 
				//Output
				$content = $list->showList($tab, $paramsTab, $listKey);
				// $debug = $list->debug();
		} else {
			$content = '&nbsp;<em>'. $description.': '._NO_DOC_IN_POSITION.'</em>';
		}
	 }
 }
 
 echo "{status : " . $status . ", content : '" . addslashes($debug.$content) . "', error : '" . addslashes($error) . "'}";
?>
