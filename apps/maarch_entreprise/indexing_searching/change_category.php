<?php
/*
*    Copyright 2008-2015 Maarch
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
* @brief  Script called by an ajax object to process the category change during indexing (index_mlb.php) : possible services from apps or module
*
* @file
* @author Claire Figueras <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup indexing_searching_mlb
*/

$core = new core_tools();
$core->load_lang();

if(!isset($_REQUEST['category_id']) || empty($_REQUEST['category_id']))
{
	$_SESSION['error'] = _CATEGORY.' '._IS_EMPTY;
	echo "{status : 1, error_txt : '".addslashes($_SESSION['error'])."'}";
	exit();
}

$services = '[';
$_SESSION['indexing_services_cat'] = array();
$_SESSION['indexing_category_id'] = $_REQUEST['category_id'];
$_SESSION['category_id'] = $_REQUEST['category_id'];
$doc_date = '';
if ($_SESSION['category_id'] == 'outgoing') {
	$doc_date = ', doc_date : "' . date('d-m-Y') . '"';
	$destination = ', destination : "'.$_SESSION['user']['primaryentity']['id']. '"';
	$_SESSION['indexing']['diff_list']['dest']['user'] = '';
	$diffListOutgoing = array(
							'user_id' => $_SESSION['user']['FirstName'],
							'lastname' => $_SESSION['user']['LastName'],
							'firstname' => $_SESSION['user']['FirstName'],
							'entity_id' => $_SESSION['user']['entities'][0]['ENTITY_ID'],
							'entity_label' => $_SESSION['user']['entities'][0]['ENTITY_LABEL'],
							'visible' => 'Y',
							'process_comment' => ''
						);

	$_SESSION['indexing']['diff_list']['dest']['user'][]=$diffListOutgoing;
} else {
	$doc_date = '';
	$destination = '';
}
// Module and apps services
$core->execute_modules_services($_SESSION['modules_services'], 'change_category.php', 'include');
$core->execute_app_services($_SESSION['app_services'], 'change_category.php', 'include');
for($i=0;$i< count($_SESSION['indexing_services_cat']);$i++)
{
	$services .= "{ script : '".$_SESSION['indexing_services_cat'][$i]['script']."', function_to_execute : '".$_SESSION['indexing_services_cat'][$i]['function_to_execute']."', arguments : '[";
	for($j=0;$j<count($_SESSION['indexing_services_cat'][$i]['arguments']);$j++)
	{
		$services .= " { id : \'".$_SESSION['indexing_services_cat'][$i]['arguments'][$j]['id']."\', value : \'".addslashes($_SESSION['indexing_services_cat'][$i]['arguments'][$j]['value'])."\' }, ";
	}
	$services = preg_replace('/, $/', '', $services);
	$services .= "]' }, ";
}
$services = preg_replace('/, $/', '', $services);
$services .= ']';
unset($_SESSION['indexing_category_id']);
unset($_SESSION['indexing_services_cat']);
echo "{status : 0,  services : ".$services . $doc_date . $destination ."}";
exit();
