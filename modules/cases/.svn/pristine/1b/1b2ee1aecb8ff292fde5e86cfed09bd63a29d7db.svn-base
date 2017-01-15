<?php
/*
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
* @brief  Displays a document logs
*
* @file hist_doc.php
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup indexing_searching_mlb
*/
require_once("core".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."class_security.php");
require_once("modules".DIRECTORY_SEPARATOR."cases".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR.'class_modules_tools.php');


$core_tools = new core_tools();
$core_tools->test_user();
$core_tools->load_lang();
$core_tools->load_html();
$core_tools->load_header('', true, false);
$sec = new security();
$cases = new cases();
?>


<body id="hist_courrier_frame">
<?php

$db = new Database();

$array_what = array();
$couleur=0;
if(isset($_SESSION['collection_id_choice']) && !empty($_SESSION['collection_id_choice']))
{
	$table = $sec->retrieve_table_from_coll($_SESSION['collection_id_choice']);
	$view = $sec->retrieve_view_from_coll_id($_SESSION['collection_id_choice']);
    $array_what[] = $table;
    $array_what[] = $view;
}
else
{
	$table = $_SESSION['collections'][0]['table'];
	$view = $_SESSION['collections'][0]['view'];
    $array_what[] = $table;
    $array_what[] = $view;
}

//Listing only document in this case...

//Get the case information
$case_limitation = " and record_id = ? ";

//Get the entire doc library
$docs_library = $cases->get_res_id($_SESSION['cases']['actual_case_id']);
$docs_limitation = ' and record_id in( ';

if(count($docs_library) >1) {
	foreach($docs_library as $tmp_implode)
	{
		$docs_limitation .= '?,';
        $array_what[] = $tmp_implode;
	}
	$docs_limitation = substr($docs_limitation, 0,-1);
} else{
    $docs_limitation .= '?';
    $array_what[] = $docs_library[0];
}
$docs_limitation .= ' ) ';

$array_what[] = $_SESSION['tablename']['cases'];

$array_what[] = $_SESSION['cases']['actual_case_id'];

$stmt = $db->query(
    "select info, event_date, user_id  from ".$_SESSION['tablename']['history']
    . " WHERE (table_name in (?, ?) ".$docs_limitation.")"
    . " OR (table_name= ? ".$case_limitation.") ORDER  BY event_date desc"
    ,$array_what);

?>
<table cellpadding="0" cellspacing="0" border="0" class="listing">
    <thead>
        <tr>
            <th><?php echo _DATE;?></th>
            <th><?php echo _USER;?> </th>
            <th><?php echo _DONE;?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $color = ' class="col"';
        while($res_hist=$stmt->fetchObject())
        {
            if($color == ' class="col"')
            {
                $color = '';
            }
            else
            {
                $color = ' class="col"';
            }

            $stmt2 = $db->query(
                "select lastname, firstname from ".$_SESSION['tablename']['users']
                . " where user_id = ?"
                ,array($res_hist->user_id));

            $res_hist2 = $stmt2->fetchObject();
            $nom = $res_hist2->lastname;
            $prenom = $res_hist2->firstname;
            ?>
            <tr <?php echo $color;?>>
                <td><span><?php functions::xecho(functions::dateformat($res_hist->event_date));?></span></td>
                <td><span><?php functions::xecho($prenom." ".$nom." ");?></span></td>
                <td><span><?php functions::xecho($res_hist->info);?></span></td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>
<?php $core_tools->load_js();?>
</body>
</html>
