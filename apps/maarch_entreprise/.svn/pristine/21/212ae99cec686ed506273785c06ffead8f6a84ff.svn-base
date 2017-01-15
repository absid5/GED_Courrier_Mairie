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
* @brief  Form to modify user password at the first connexion
*
*
* @file
* @author  Claire Figueras  <dev@maarch.org>
* @date $date$
* @version $Revision$
* @ingroup admin
*/

core_tools::load_lang();
if(!isset($_SESSION['config']['userdefaultpassword']) || empty($_SESSION['config']['userdefaultpassword']))
	$_SESSION['config']['userdefaultpassword'] = 'maarch';
?>
<h2 class="tit"><?php echo _PASSWORD_MODIFICATION;?></h2>
<div id="frm_error"></div>
<p ><?php echo _PASSWORD_FOR_USER;?> <b><?php functions::xecho($_SESSION['m_admin']['users']['user_id'] );?></b> <?php echo _HAS_BEEN_RESET;?>.
</p>
<p><?php echo _NEW_PASW_IS." '".$_SESSION['config']['userdefaultpassword']."'";?></p>
<p >
<?php echo _DURING_NEXT_CONNEXION;?>, <?php functions::xecho($_SESSION['m_admin']['users']['user_id'] );?> <?php echo _MUST_CHANGE_PSW;?>.
</p>
<br/>
<p class="buttons" ><input type="button" class="button" onclick="changePassword('<?php echo $_SESSION['config']['businessappurl'].'index.php?display=true&admin=users&page=manage_psw_changed';?>');" name="close" value="<?php echo _CLOSE_WINDOW;?>" /></p>
