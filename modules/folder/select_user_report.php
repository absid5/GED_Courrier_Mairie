<?php

/*
*    Copyright 2008 - 2015 Maarch
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

$core_tools = new core_tools();
$core_tools->test_user();
$core_tools->load_lang();

require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_list_show.php");
$func = new functions();

$what = "all";
$where = "";
$arrayPDO = array();
$_SESSION['chosen_user'] = '';
if(isset($_GET['what']) && !empty($_GET['what']))
{
	if($_GET['what'] == "all")
	{
		$what = "all";

	}
	else
	{
		$what = $func->wash($_GET['what'], "no", "", "no");
		$where = " lower(".$_SESSION['tablename']['users'].".lastname) like lower(?) ";
		$arrayPDO = array_merge($arrayPDO, array($what.'%'));
	}
}

	$select[$_SESSION['tablename']['users']] = array();
	array_push($select[$_SESSION['tablename']['users']],"user_id","lastname","firstname" );

	$req = new request();

	$list=new list_show();
	$order = 'asc';
	if(isset($_REQUEST['order']) && !empty($_REQUEST['order']))
	{
		$order = trim($_REQUEST['order']);
	}
	$field = 'lastname';
	if(isset($_REQUEST['order_field']) && !empty($_REQUEST['order_field']))
	{
		$field = trim($_REQUEST['order_field']);
	}

	$orderstr = $list->define_order($order, $field);

	$tab = $req->PDOselect($select, $where, $arrayPDO, $orderstr, $_SESSION['config']['databasetype'], $limit="500",false);

for ($i=0;$i<count($tab);$i++)
{
		for ($j=0;$j<count($tab[$i]);$j++)
		{
			foreach(array_keys($tab[$i][$j]) as $value)
			{


				if($tab[$i][$j][$value]== "user_id" )
				{
					$tab[$i][$j]["user_id"]= $tab[$i][$j]['value'];
					$tab[$i][$j]["label"]= _ID;
					$tab[$i][$j]["size"]="30";
					$tab[$i][$j]["label_align"]="left";
					$tab[$i][$j]["align"]="left";
					$tab[$i][$j]["valign"]="bottom";
					$tab[$i][$j]["show"]=true;
					$tab[$i][$j]["order"]='user_id';
				}

				if($tab[$i][$j][$value]=='lastname')
				{
					$tab[$i][$j]['value']= $req->show_string($tab[$i][$j]['value']);
					$tab[$i][$j]['lastname']= $tab[$i][$j]['value'];
					$tab[$i][$j]["label"]=_LASTNAME;
					$tab[$i][$j]["size"]="30";
					$tab[$i][$j]["label_align"]="left";
					$tab[$i][$j]["align"]="left";
					$tab[$i][$j]["valign"]="bottom";
					$tab[$i][$j]["show"]=true;
					$tab[$i][$j]["order"]='lastname';
				}
				if($tab[$i][$j][$value]=="firstname")
				{
					$tab[$i][$j]['value']= $req->show_string($tab[$i][$j]['value']);
					$tab[$i][$j]["info"]= $tab[$i][$j]['value'];
					$tab[$i][$j]["label"]=_FIRSTNAME;
					$tab[$i][$j]["size"]="30";
					$tab[$i][$j]["label_align"]="left";
					$tab[$i][$j]["align"]="left";
					$tab[$i][$j]["valign"]="bottom";
					$tab[$i][$j]["show"]=true;
					$tab[$i][$j]["order"]='firstname';
				}
			}
		}
	}

	if(isset($_REQUEST['field']) && !empty($_REQUEST['field']))
	{
		//$_SESSION['chosen_user'] = $_REQUEST['field'];
		?>
			<script type="text/javascript">
				var item = window.opener.$('user_id');
				if(item)
				{
					item.value = '<?php functions::xecho($_REQUEST['field']);?>';
					self.close();
				}
			</script>
			<?php
		exit();
	}

//here we loading the html
$core_tools->load_html();
//here we building the header
$core_tools->load_header(_CHOOSE_USER2, true, false);
$time = $core_tools->get_session_time_expire();
?>
<body onload="javascript:setTimeout(window.close, <?php echo $time;?>*60*1000);">

<?php
$nb = count($tab);

$list->list_doc($tab, $nb, _USERS_LIST,'user_id',$name = "select_user_report",'user_id','',false,true,'get',$_SESSION['config']['businessappurl'].'index.php?display=true&module=folder&page=select_user_report',_CHOOSE_USER2, false, true, true,false, true, true,  true, false, '', '',  true, _ALL_USERS,_USER, 'listing spec', '', false, false, array(), '<input type="hidden" name="display" value="true"/><input type="hidden" name="module" value="folder" /><input type="hidden" name="page" value="select_user_report" />');
$core_tools->load_js();
?>
</body>
</html>
