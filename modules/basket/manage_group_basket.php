<?php
/*
*
*    Copyright 2008,2009 Maarch
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
* @brief   Manages  results of the groupbasket_popup.php file
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup basket
*/

require_once("modules".DIRECTORY_SEPARATOR."basket".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_admin_basket.php");

$core_tools = new core_tools();
$core_tools->load_lang();
$_SESSION['service_tag'] = 'manage_groupbasket';

if(isset($_REQUEST['cancel']))
{
	unset($_SESSION['m_admin']['basket_popup']);
	?>
	<script type="text/javascript">window.top.close();</script>
	<?php
	exit();
}
$groupe = "";
$bask = new admin_basket();
$func = new functions();
$db = new Database();
$_SESSION['m_admin']['basket_popup'] = array();
if(isset($_REQUEST['group']) && !empty($_REQUEST['group']))
{
	$groupe = $_REQUEST['group'];
	$_SESSION['m_admin']['basket_popup']['group_id'] = $groupe;
}
else
{
	$_SESSION['error'] .= _NO_GROUP_SELECTED.".";
}
if(isset($_REQUEST['result_page']) && !empty($_REQUEST['result_page']))
{
	$respage = $_REQUEST['result_page'];
	$_SESSION['m_admin']['basket_popup']['res_page'] = $respage;
}
else
{
	$_SESSION['error'] .= _NO_RESULT_PAGE_SELECTED.".";
}
    //Lock list
    $list_whrere_clause = $sublist_whrere_clause ='';
    if (isset($_REQUEST['lock_list']) && $_REQUEST['lock_list'] == 'Y') {
        if (!empty($_REQUEST['list_whrere_clause'])) {
            $list_whrere_clause = trim($_REQUEST['list_whrere_clause']);
            $_SESSION['m_admin']['basket_popup']['list_whrere_clause'] = trim($_REQUEST['list_whrere_clause']);
        }
        if (!empty($_REQUEST['sublist_whrere_clause'])) {
            $sublist_whrere_clause = trim($_REQUEST['sublist_whrere_clause']);
            $_SESSION['m_admin']['basket_popup']['sublist_whrere_clause'] = trim($_REQUEST['sublist_whrere_clause']);
        }
        if (strlen(trim($list_whrere_clause)) == 0 && strlen(trim($sublist_whrere_clause)) == 0) {
            $_SESSION['error'] .= _SYNTAX_ERROR_LOCK_CLAUSE.".";
        }
    }
    
	$old_group = "";
	$seq = "";

	if(isset($_REQUEST['old_group']) && !empty($_REQUEST['old_group']))
	{
		$old_group = $_REQUEST['old_group'];
	}

	$stmt = $db->query("select max(sequence) as seq from ".$_SESSION['tablename']['bask_groupbasket']." where group_id = ?",array($groupe));
	$line = $stmt->fetchObject();
	$seq = $line->seq +1;

	$actions = array();

	$_SESSION['m_admin']['basket_popup']['actions'] = array();
	if(count($_REQUEST['actions']) > 0)
	{
		for($i=0; $i < count($_REQUEST['actions']); $i++)
		{
			$where = '';
			if(isset($_REQUEST['whereclause_'.$_REQUEST['actions'][$i]]) && !empty($_REQUEST['whereclause_'.$_REQUEST['actions'][$i]]))
			{
				$where = $_REQUEST['whereclause_'.$_REQUEST['actions'][$i]];
			}
			$stmt = $db->query("select label_action from ".$_SESSION['tablename']['actions']." where id = ?",array($_REQUEST['actions'][$i]));
			$res = $stmt->fetchObject();
			$syntax =  $bask->where_test($where);

			if($syntax['status'] <> true)
			{
				$_SESSION['error'] .= ' : ' . _SYNTAX_ERROR_WHERE_CLAUSE . ' ' . $syntax['error'] ._IN_ACTION.' '.$res->label_action.'<br/>' ;;
			}
			if(isset($_REQUEST['action_'.$_REQUEST['actions'][$i].'_mass_use']) && !empty($_REQUEST['action_'.$_REQUEST['actions'][$i].'_mass_use']))
			{
				$mass = $_REQUEST['action_'.$_REQUEST['actions'][$i].'_mass_use'];
			}
			else
			{
				$mass = 'N';
			}
			if(isset($_REQUEST['action_'.$_REQUEST['actions'][$i].'_page_use']) && !empty($_REQUEST['action_'.$_REQUEST['actions'][$i].'_page_use']))
			{
				$page = $_REQUEST['action_'.$_REQUEST['actions'][$i].'_page_use'];
			}
			else
			{
				$page = 'N';
			}

			if($page == 'N' && $mass == 'N')
			{
				$_SESSION['error'] .= " "._MUST_CHOOSE_WHERE_USE_ACTION." : ".$res->label_action.'<br/>' ;
			}

			$_SESSION['m_admin']['basket_popup']['actions'][$_REQUEST['actions'][$i]] = array( 'WHERE' => $where, 'MASS_USE' => $mass, 'PAGE_USE' => $page);

			$tmp_action = array('ID_ACTION' => $_REQUEST['actions'][$i], 'LABEL_ACTION' => $res->label_action, 'WHERE' => $where, 'MASS_USE' => $mass, 'PAGE_USE' => $page);
			array_push($actions, $tmp_action);
		}
	}

	if(isset($_REQUEST['default_action_page']) && !empty($_REQUEST['default_action_page']))
	{
		$default_action_page = $_REQUEST['default_action_page'];
		$_SESSION['m_admin']['basket_popup']['default_action_page'] = $default_action_page;
	}
	if(empty($_SESSION['error']))
	{
		$stmt = $db->query("select group_desc from ".$_SESSION['tablename']['usergroups']." where group_id = ?",array($groupe));
		$res = $stmt->fetchObject();
		$tab = array(
                "GROUP_ID"          => $groupe, 
                "GROUP_LABEL"       => $res->group_desc, 
                "SEQUENCE"          => $seq, 
                "RESULT_PAGE"       => $respage, 
                "LOCK_LIST"         => $list_whrere_clause, 
                "LOCK_SUBLIST"      => $sublist_whrere_clause, 
                "DEFAULT_ACTION"    => $default_action_page,  
                "ACTIONS" => $actions);
		$find = false;
		for($i=0; $i < count($_SESSION['m_admin']['basket']['groups']); $i++)
		{
			if($_SESSION['m_admin']['basket']['groups'][$i]['GROUP_ID'] == $groupe)
			{
				$_SESSION['m_admin']['basket']['groups'][$i] = $tab;
				$find = true;
				break;
			}
			if($old_group == $_SESSION['m_admin']['basket']['groups'][$i]['GROUP_ID'])
			{
				$_SESSION['m_admin']['basket']['groups'][$i] = $tab;
				$find = true;
				break;
			}
		}

		if(!$find)
		{
			array_push($_SESSION['m_admin']['basket']['groups'], $tab);
		}
		echo $core_tools->execute_modules_services($_SESSION['modules_services'], 'manage_group_basket.php', "include");
		echo $core_tools->execute_app_services($_SESSION['app_services'], 'manage_group_basket.php', "include");
	}

if(!empty($_SESSION['error']))
{
	header("location: ".$_SESSION['config']['businessappurl']."index.php?display=true&module=basket&page=groupbasket_popup&id=".$groupe);
	exit();
}
$_SESSION['service_tag'] = '';
//echo "<pre>" . print_r($_SESSION['m_admin']['basket'],true) . "</pre>"; exit();
unset($_SESSION['m_admin']['basket_popup']);
?>
<script type="text/javascript">
	window.parent.opener.location.reload();self.close();
</script>
