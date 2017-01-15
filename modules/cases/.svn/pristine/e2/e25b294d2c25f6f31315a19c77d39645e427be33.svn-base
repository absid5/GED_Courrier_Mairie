<?php  /**
* File : cases_notes_list.php
*
* Frame, shows the notes of a document
*
* @package Maarch Entreprise 1.0
* @version 1.0
* @since 06/2006
* @license GPL
* @author  Loïc Vinet  <dev@maarch.org>
*/

require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_request.php");
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_security.php");
require_once("apps".DIRECTORY_SEPARATOR.$_SESSION['config']['app_id'].DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_list_show.php");
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_manage_status.php");

require_once("modules".DIRECTORY_SEPARATOR."cases".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR.'class_modules_tools.php');
require_once "modules" . DIRECTORY_SEPARATOR . "notes" . DIRECTORY_SEPARATOR
    . "class" . DIRECTORY_SEPARATOR
    . "class_modules_tools.php";

$core_tools = new core_tools();
$core_tools->load_lang();
$core_tools->test_service('manage_notes_doc', 'notes');
$func = new functions();
$cases = new cases();
$status_obj = new manage_status();
$sec = new security();
$notes_tools = new notes();


if(empty($_SESSION['collection_id_choice']))
{
	$_SESSION['collection_id_choice']= $_SESSION['user']['collections'][0];
}
$where_request = '';
if (isset($_SESSION['searching']['cases_request'])) {
    $where_request = $_SESSION['searching']['cases_request'];
}

$status = $status_obj->get_not_searchable_status();
$status_str = '';
$where_what = array();
for($i=0; $i<count($status);$i++)
{
	$status_str .=	"?,";
	$where_what[] = $status[$i]['ID'];
}
if ($status_str <> '') {
    $status_str = preg_replace('/,$/', '', $status_str);
    $where_request.= "  status not in (".$status_str.") ";
    
} else {
    $where_request .= " 1=1 ";
}

$where_clause = $sec->get_where_clause_from_coll_id($_SESSION['collection_id_choice']);

if(!empty($where_request))
{
	if (isset($_SESSION['searching']['where_clause_bis'])
	    && $_SESSION['searching']['where_clause_bis'] <> ""
    ) {
		$where_clause = "((".$where_clause.") or (".$_SESSION['searching']['where_clause_bis']."))";
	}
	$where_request = '('.$where_request.') and ('.$where_clause.')';
}
else
{
	if($_SESSION['searching']['where_clause_bis'] <> "")
	{
		$where_clause = "((".$where_clause.") or (".$_SESSION['searching']['where_clause_bis']."))";
	}
	$where_request = $where_clause;
}


$where_request = str_replace("()", "(1=-1)", $where_request);
$where_request = str_replace("and ()", "", $where_request);

$request= new request();
$select = array();
$view = $sec->retrieve_view_from_coll_id($_SESSION['collection_id_choice'] );


$select[$view] = array();
array_push($select[$view], "res_id", "alt_identifier", "subject", "dest_user", "type_label", "creation_date", "destination", "category_id, exp_user_id", "category_id as category_img");
$select[$_SESSION['tablename']['not_notes']] = array();
array_push($select[$_SESSION['tablename']['not_notes']],"id", "date_note", "note_text", "user_id");

$where_request .= " and ".$_SESSION['tablename']['not_notes'].".identifier = ".$view.".res_id";


//Listing only document in this case...

//Get the entire doc library
$docs_library = $cases->get_res_id($_SESSION['cases']['actual_case_id']);
$docs_limitation = ' and res_id in( ';

if(count($docs_library) >1)
{
	foreach($docs_library as $tmp_implode)
	{
		$docs_limitation .= $tmp_implode.',';
	}
	$docs_limitation = substr($docs_limitation, 0,-1);
}
else
$docs_limitation .= $docs_library[0];
$docs_limitation .= ' ) ';



$tabNotes=$request->PDOselect($select,$where_request.$docs_limitation,$where_what,"order by ".$view.".res_id",$_SESSION['config']['databasetype'], "500", false );
$ind_notes1d = '';





if($_GET['size'] == "full")
{
	$size_medium = "15";
	$size_small = "15";
	$size_full = "70";
	$css = "listing spec detailtabricatordebug";
	$body = "";
	$cut_string = 100;
	$extend_url = "&size=full";
}
else
{
	$size_medium = "18";
	$size_small = "10";
	$size_full = "30";
	$css = "listingsmall";
	$body = "iframe";
	$cut_string = 20;
	$extend_url = "";
}

$arrayToUnset = array();

for ($indNotes1 = 0; $indNotes1 < count($tabNotes); $indNotes1 ++ ) {
	for ($indNotes2 = 0; $indNotes2 < count($tabNotes[$indNotes1]); $indNotes2 ++) {
		foreach (array_keys($tabNotes[$indNotes1][$indNotes2]) as $value) {
			if ($tabNotes[$indNotes1][$indNotes2][$value] == "id") {
				$tabNotes[$indNotes1][$indNotes2]["id"] = $tabNotes[$indNotes1][$indNotes2]['value'];
				$tabNotes[$indNotes1][$indNotes2]["label"] = 'ID';
				$tabNotes[$indNotes1][$indNotes2]["size"] = $sizeSmall;
				$tabNotes[$indNotes1][$indNotes2]["label_align"] = "left";
				$tabNotes[$indNotes1][$indNotes2]["align"] = "left";
				$tabNotes[$indNotes1][$indNotes2]["valign"] = "bottom";
				$tabNotes[$indNotes1][$indNotes2]["show"] = true;
				$indNotes1d = $tabNotes[$indNotes1][$indNotes2]['value'];
				if (!$notes_tools->isUserNote(
					$tabNotes[$indNotes1][$indNotes2]['value'], 
					$_SESSION['user']['UserId'], 
					$_SESSION['user']['primaryentity']['id']
					)
				) {
					//unset($tabNotes[$indNotes1]);
					//echo 'sort ' . $indNotes1 . '<br>';
					array_push($arrayToUnset, $indNotes1);
				} else {

				}
			}
		}
	}
}

for ($cptUnset=0;$cptUnset<count($arrayToUnset);$cptUnset++ ) {
	unset($tabNotes[$arrayToUnset[$cptUnset]]);
}
// array_multisort($tabNotes, SORT_DESC);
$tabNotes = array_merge($tabNotes);

for ($ind_notes1=0;$ind_notes1<count($tabNotes);$ind_notes1++)
{
	for ($ind_notes2=0;$ind_notes2<count($tabNotes[$ind_notes1]);$ind_notes2++)
	{
		foreach(array_keys($tabNotes[$ind_notes1][$ind_notes2]) as $value)
		{
			if($tabNotes[$ind_notes1][$ind_notes2][$value]=="id")
			{
				$tabNotes[$ind_notes1][$ind_notes2]["id"]=$tabNotes[$ind_notes1][$ind_notes2]['value'];
				$tabNotes[$ind_notes1][$ind_notes2]["label"]= _ID;
				$tabNotes[$ind_notes1][$ind_notes2]["size"]=$size_small;
				$tabNotes[$ind_notes1][$ind_notes2]["label_align"]="left";
				$tabNotes[$ind_notes1][$ind_notes2]["align"]="left";
				$tabNotes[$ind_notes1][$ind_notes2]["valign"]="bottom";
				$tabNotes[$ind_notes1][$ind_notes2]["show"]=false;
				$ind_notes1d = $tabNotes[$ind_notes1][$ind_notes2]['value'];
			}
			if($tabNotes[$ind_notes1][$ind_notes2][$value]=="res_id")
			{
				$tabNotes[$ind_notes1][$ind_notes2]["id"]=$tabNotes[$ind_notes1][$ind_notes2]['value'];
				$tabNotes[$ind_notes1][$ind_notes2]["label"]= _NUM_GED;
				$tabNotes[$ind_notes1][$ind_notes2]["size"]=$size_small;
				$tabNotes[$ind_notes1][$ind_notes2]["label_align"]="left";
				$tabNotes[$ind_notes1][$ind_notes2]["align"]="left";
				$tabNotes[$ind_notes1][$ind_notes2]["valign"]="bottom";
				if(_ID_TO_DISPLAY == 'res_id'){
					$display=true;
				}else{
					$display=false;
				}
				$tabNotes[$ind_notes1][$ind_notes2]["show"]=$display;
				$ind_notes1d = $tabNotes[$ind_notes1][$ind_notes2]['value'];
			}
			if($tabNotes[$ind_notes1][$ind_notes2][$value]=="alt_identifier")
			{
				$tabNotes[$ind_notes1][$ind_notes2]["id"]=$tabNotes[$ind_notes1][$ind_notes2]['value'];
				$tabNotes[$ind_notes1][$ind_notes2]["label"]= _CHRONO_NUMBER;
				$tabNotes[$ind_notes1][$ind_notes2]["size"]=$size_small;
				$tabNotes[$ind_notes1][$ind_notes2]["label_align"]="left";
				$tabNotes[$ind_notes1][$ind_notes2]["align"]="left";
				$tabNotes[$ind_notes1][$ind_notes2]["valign"]="bottom";
				if(_ID_TO_DISPLAY == 'chrono_number'){
					$display=true;
				}else{
					$display=false;
				}
				$tabNotes[$ind_notes1][$ind_notes2]["show"]=$display;
				$ind_notes1d = $tabNotes[$ind_notes1][$ind_notes2]['value'];
			}
			if($tabNotes[$ind_notes1][$ind_notes2][$value]=="user_id")
			{
				$tabNotes[$ind_notes1][$ind_notes2]["user_id"]=$tabNotes[$ind_notes1][$ind_notes2]['value'];
				$tabNotes[$ind_notes1][$ind_notes2]["label"]= _ID;
				$tabNotes[$ind_notes1][$ind_notes2]["size"]=$size_small;
				$tabNotes[$ind_notes1][$ind_notes2]["label_align"]="left";
				$tabNotes[$ind_notes1][$ind_notes2]["align"]="left";
				$tabNotes[$ind_notes1][$ind_notes2]["valign"]="bottom";
				$tabNotes[$ind_notes1][$ind_notes2]["show"]=false;
			}
			if($tabNotes[$ind_notes1][$ind_notes2][$value]=="lastname")
			{
				$tabNotes[$ind_notes1][$ind_notes2]['value']=$request->show_string($tabNotes[$ind_notes1][$ind_notes2]['value']);
				$tabNotes[$ind_notes1][$ind_notes2]["lastname"]=$tabNotes[$ind_notes1][$ind_notes2]['value'];
				$tabNotes[$ind_notes1][$ind_notes2]["label"]=_LASTNAME;
				$tabNotes[$ind_notes1][$ind_notes2]["size"]=$size_small ;
				$tabNotes[$ind_notes1][$ind_notes2]["label_align"]="left";
				$tabNotes[$ind_notes1][$ind_notes2]["align"]="left";
				$tabNotes[$ind_notes1][$ind_notes2]["valign"]="bottom";
				$tabNotes[$ind_notes1][$ind_notes2]["show"]=true;
			}
			if($tabNotes[$ind_notes1][$ind_notes2][$value]=="date_note")
			{
				$tabNotes[$ind_notes1][$ind_notes2]["date_note"]=$tabNotes[$ind_notes1][$ind_notes2]['value'];
				$tabNotes[$ind_notes1][$ind_notes2]["value"] = $core_tools->format_date_db($tabNotes[$ind_notes1][$ind_notes2]['value'], false, '', true);
				$tabNotes[$ind_notes1][$ind_notes2]["label"]=_DATE;
				$tabNotes[$ind_notes1][$ind_notes2]["size"]=$size_small;
				$tabNotes[$ind_notes1][$ind_notes2]["label_align"]="left";
				$tabNotes[$ind_notes1][$ind_notes2]["align"]="left";
				$tabNotes[$ind_notes1][$ind_notes2]["valign"]="bottom";
				$tabNotes[$ind_notes1][$ind_notes2]["show"]=true;
			}
			if($tabNotes[$ind_notes1][$ind_notes2][$value]=="firstname")
			{
				$tabNotes[$ind_notes1][$ind_notes2]["firstname"]= $tabNotes[$ind_notes1][$ind_notes2]['value'];
				$tabNotes[$ind_notes1][$ind_notes2]["label"]=_FIRSTNAME;
				$tabNotes[$ind_notes1][$ind_notes2]["size"]=$size_small;
				$tabNotes[$ind_notes1][$ind_notes2]["label_align"]="center";
				$tabNotes[$ind_notes1][$ind_notes2]["align"]="center";
				$tabNotes[$ind_notes1][$ind_notes2]["valign"]="bottom";
				$tabNotes[$ind_notes1][$ind_notes2]["show"]=true;
			}
			if($tabNotes[$ind_notes1][$ind_notes2][$value]=="note_text")
			{
				$tabNotes[$ind_notes1][$ind_notes2]['value'] = '<a href="javascript://" onclick="ouvreFenetre(\''.$_SESSION['config']['businessappurl'].'index.php?display=true&module=notes&page=note_details&id='.$ind_notes1d.'&amp;resid='.$_SESSION['doc_id'].'&amp;coll_id='.$_SESSION['collection_id_choice'].$extend_url.'\', 450, 300)">'.$func->cut_string($request->show_string($tabNotes[$ind_notes1][$ind_notes2]['value']), $cut_string).'<span class="sstit"> > '._READ.'</span>';
				$tabNotes[$ind_notes1][$ind_notes2]["note_text"]= $tabNotes[$ind_notes1][$ind_notes2]['value'];
				$tabNotes[$ind_notes1][$ind_notes2]["label"]=_NOTES;
				$tabNotes[$ind_notes1][$ind_notes2]["size"]=$size_full;
				$tabNotes[$ind_notes1][$ind_notes2]["label_align"]="center";
				$tabNotes[$ind_notes1][$ind_notes2]["align"]="center";
				$tabNotes[$ind_notes1][$ind_notes2]["valign"]="bottom";
				$tabNotes[$ind_notes1][$ind_notes2]["show"]=true;
			}
			if($tabNotes[$ind_notes1][$ind_notes2][$value]=="subject")
			{
				$tabNotes[$ind_notes1][$ind_notes2]["subject"]=$tabNotes[$ind_notes1][$ind_notes2]['value'];
				$tabNotes[$ind_notes1][$ind_notes2]["label"]=_SUBJECT;
				$tabNotes[$ind_notes1][$ind_notes2]["size"]=30;
				$tabNotes[$ind_notes1][$ind_notes2]["label_align"]="left";
				$tabNotes[$ind_notes1][$ind_notes2]["align"]="left";
				$tabNotes[$ind_notes1][$ind_notes2]["valign"]="bottom";
				$tabNotes[$ind_notes1][$ind_notes2]["show"]=true;
			}
			//var_dump($size_full);
			//var_dump($tabNotes[$ind_notes1][$ind_notes2][$value]);
		}
	}
}
//$request->show_array($tabNotes);
$core_tools->load_html();
//here we building the header
$core_tools->load_header('', true, false);
?>
<body id="<?php functions::xecho($body);?>">
<?php
$title = '';
$list_notes = new list_show();
$list_notes->list_simple($tabNotes, count($tabNotes), $title,'id','id', false, '',$css);
$core_tools->load_js();
?>
</body>
</html>

