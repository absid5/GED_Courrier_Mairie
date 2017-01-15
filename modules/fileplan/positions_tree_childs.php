<?php

if(
	(isset($_POST['fileplan_id']) && !empty($_POST['fileplan_id'])) 
	&& (isset($_POST['branch_id']) && !empty($_POST['branch_id']))
) {
	//Single or composed ID?
	if (strpos($_POST['branch_id'], '@@') !== false) {
		$branch_array = array();
		$branch_array = explode('@@', $_POST['branch_id']);
	
		$fileplan_id = $branch_array[0];
		$position_id = $branch_array[1];
	} else {
		$fileplan_id = $_POST['fileplan_id'];
		$position_id = $_POST['branch_id'];
	}

	//Same fileplan ID?
	($_POST['fileplan_id'] == $fileplan_id )? $fileplan_id = $fileplan_id : $fileplan_id = $_POST['fileplan_id'];
	
    require_once "core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php";
    require_once "modules" . DIRECTORY_SEPARATOR . "fileplan" . DIRECTORY_SEPARATOR
        . "class" . DIRECTORY_SEPARATOR . "class_modules_tools.php";
    
    $core_tools = new core_tools();
    $db         = new Database();
    $fileplan   = new fileplan();
    
    $core_tools->load_lang();
    $childrens = array();

    $stmt = $db->query(
        "select fileplan_id , position_id, position_label from "
        . FILEPLAN_VIEW." where fileplan_id = ?" 
        . " and parent_id = ?"
        . " and position_enabled = ? order by position_label asc"
        ,array($fileplan_id,$position_id,'Y'));

    // $db->show();

    if($stmt->rowCount() > 0) {
        while($res = $stmt->fetchObject()) {
            array_push(
               $childrens,
               array(
                    'id' => $res->fileplan_id.'@@'.$res->position_id, 
                    'tree' => '0', 
                    'key_value' => $res->position_id, 
                    'label_value' => functions::show_string($fileplan->truncate($res->position_label), true), 
                    'tooltip_value' => functions::show_string($res->position_label, true), 
                    'canhavechildren' => 'true'
                )
            );
        }
    }
    //print_r($childrens);exit;
    if(count($childrens) > 0) {
		(isset($_POST['origin']) && $_POST['origin'] == 'admin')? $view_documents = "" : $view_documents = ", onclick : 'view_document_list'";
        echo '[';
        for($i=0; $i< count($childrens); $i++) {
            echo "
                {id : '".$childrens[$i]['id']
                ."', title : '".addslashes($childrens[$i]['tooltip_value'])
                ."', canhavechildren : '".$childrens[$i]['canhavechildren']
                ."'".$view_documents
                .", txt : '&nbsp;".addslashes($childrens[$i]['label_value'])."'"
                .", style : 'tree_branch'},";
        }
        echo ']';
    }
}
?>