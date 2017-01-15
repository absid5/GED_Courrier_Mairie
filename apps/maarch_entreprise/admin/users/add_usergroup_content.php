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
* @brief Form to add a grant to a user, pop up page (User administration)
*
*
* @file
* @author  Claire Figueras  <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup admin
*/

try{
    require_once("core/class/usergroups_controler.php");
} catch (Exception $e){
    functions::xecho($e->getMessage());
}
core_tools::load_lang();
core_tools::test_admin('admin_users', 'apps');

$ugc = new usergroups_controler();
$tab = $ugc->getAllUsergroups();

$tab2 = array();
if ( count($_SESSION['m_admin']['users']['groups']) > 0 )
{
    for($i=0; $i < count($_SESSION['m_admin']['users']['groups']); $i++)
    {
        array_push($tab2, array('ID'=> $_SESSION['m_admin']['users']['groups'][$i]['GROUP_ID'], 'LABEL' => $_SESSION['m_admin']['users']['groups'][$i]['LABEL']));
    }
}

$res = $tab;
for($j=0; $j < count($tab); $j++)
{
    for($k=0; $k < count($tab2); $k++)
    {
        if($tab[$j]->__get('group_id') ==  $tab2[$k]['ID'])
        {
            unset($res[$j]);
            break;
        }
    }
}
$res = array_values($res);
unset($tab2);
unset($tab);
?>
<div class="popup_content">
<h2 class="tit"><?php echo _ADD_GROUP;?></h2>
<form name="chooseGroup" id="chooseGroup" method="get" action="#" class="forms">
<p>
    <label for="group_id"> <?php echo _CHOOSE_GROUP_ADMIN;?>&nbsp; : &nbsp;  &nbsp; </label>
    <select name="group_id" id="group_id" >
<?php

for($j=0; $j<count($res); $j++)
{
    $desc = $res[$j]->__get('group_desc');
    if(isset($res[$j]) && !empty($desc))
    {
?>
    <option value="<?php functions::xecho($res[$j]->__get('group_id')); ?>"><?php functions::xecho($res[$j]->__get('group_desc'));?></option>
<?php
    }
}
?>
</select>
</p>
<br/>
<p>
    <label for="role"><?php echo _ROLE;?> : </label>
    <input type="text"  name="role" id="role" />
</p>
<br/>
<p class="buttons">
    <input type="button" name="Submit" value="<?php echo _VALIDATE;?>" class="button" onclick="checkGroup('chooseGroup', '<?php echo $_SESSION['config']['businessappurl'].'index.php?display=true&admin=users&page=check_group';?>', '<?php echo $_SESSION['config']['businessappurl'].'index.php?display=true&amp;admin=users&amp;page=manage_group';?>', '<?php echo $_SESSION['config']['businessappurl'].'index.php?display=true&amp;admin=users&amp;page=ugc_form';?>');"  />
    <input type="button" name="cancel" value="<?php echo _CANCEL;?>" class="button"  onclick="destroyModal('add_ugc');"/>
</p>

</form>
</div>
